<?php
/**
 * @package NetxWPPlugin
 */

class NetXMediaManager {
    const NETX_ASSET_ID_KEY = '_netx_asset_id';
    const NETX_ASSET_VIEWNAME_KEY = '_netx_asset_view_name';
    const NETX_ENFORCE_DOWNLOAD_KEY = '_netx_enforce_download';
    const NETX_FULL_PROXY_URL = '_netx_proxy_url';
    const NETX_FILE_SIZE = '_netx_file_size';
    const NETX_FILE_NAME = '_netx_file_name';
    const NETX_FILE_TYPE = '_netx_file_type';

    public static function init() {
        new NetXMediaManager();
    }

    public function __construct() {
        //include our client side code
        add_action( 'admin_enqueue_scripts', array($this, 'setup_admin_client_scripts'));

        //add ajax endpoints
        add_action( 'wp_ajax_netx_load', array($this, 'ajax_load'));
        add_action( 'wp_ajax_netx_get_image_select_form', array($this, 'get_image_select_form'));
        add_action( 'wp_ajax_netx_import_from_netx', array($this, 'import_from_netx'));

        //add our custom html filter.
        add_filter('image_send_to_editor', array($this, 'wrap_image_html'), 10, 8);
        
        if (defined('WP_DEBUG_LOG') && true === WP_DEBUG_LOG) {
            $config = netxConfig::getInstance();
            $config->setHttpLoggingOn(true);
            $config->setApiLoggingOn(true);
        }
    }

    public function setup_admin_client_scripts() {
        wp_enqueue_style('netx_style', plugins_url('scripts/style.css', __FILE__ ));
        wp_enqueue_script('netx_script', plugins_url('scripts/script.js', __FILE__ ));
        wp_localize_script('netx_script', 'netxScript', array(
            'ajaxLoaderUrl' => plugins_url('scripts/ajax-loader.gif', __FILE__ ),
            'debug' => "0"
        ));
    }

    //Ajax Callback. Returns the netx form loader as html
    public function ajax_load() {
        $wrapper = new netxRestWrapper();
        $netx = $wrapper->getNetx();
        $cats = $netx->getCategoryTree();
        $options = get_option('netx_options');
        ?>
        <form id="netx-form" class="media-upload-form validate" action="<?php echo get_bloginfo("url"); ?>/wp-admin/media-upload.php?post_id=<?php echo $postID; ?>&tab=netx" method="post" enctype="multipart/form-data">
            <div id="category-tree">
                <ul>
                    <?php $this->tree_node_view($this->getRootCategoryFromTree($cats['1']['children'], $options['netx_base_category_id']), $options['netx_base_category_id']); ?>
                </ul>
            </div>

            <div id="media-items">
                <div class="netx-upload-loading-area">
                    <div class="netx-spinner">
                        <div class="netx-dot1"></div>
                        <div class="netx-dot2"></div>
                    </div>
                </div>
            </div>

            <div class="clear-fix"></div>
        </form>
        <?php
        wp_die();
    }

    public function import_from_netx() {
        $wrapper = new netxRestWrapper();
        $netx = $wrapper->getNetx();

        $assetID = $_POST['asset_id'];
        $assetView = $_POST['asset_view'];

        $attachment_id = $this->AddMediaToLibraryFromNetx($wrapper, $netx, $assetID, $assetView, $_POST['enforce_download'] == 'true')['attachment_id'];

        echo($attachment_id);

        wp_die();
    }

    //Ajax Callback. Returns the netx form as html.
    public function get_image_select_form() {
        $options = get_option('netx_options');
        $wrapper = new netxRestWrapper();
        $netx = $wrapper->getNetx();

        $supportedFileTypes = array(
            //images
            "jpg",
            "jpeg",
            "png",
            "gif",
            "ico",
            //documents
            "pdf",
            "doc",
            "ppt",
            "odt",
            "xls",
            "psd",
            //audio
            "mp3",
            "m4a",
            "ogg",
            "wav",
            //video
            "mp4",
            "mov",
            "wmv",
            "avi",
            "mpg",
            "ogv",
            "3gp",
            "3g2"
        );

        $pagingSize = intval($options['netx_paging_size']);
        if($pagingSize == 0) {
            $pagingSize = 200;
        }

        //get current category id
        $currentCatId = (trim($_POST['catId']) != '') ? $_POST['catId'] : $options['netx_base_category_id'];

        //get assets in current category
        $catAssets = $netx->getAssetsByCategoryID($currentCatId);
        $catAssetsNum = 0;
        foreach($catAssets as $key=>$asset){
            $catAssetsNum++;
        }

        $postID = intval($_REQUEST['post_id']);
//    $proxyURL = dirname(__FILE__) . '/proxy.php'
        ?>
        <script type="text/javascript">post_id = <?php echo $postID ?>;</script>
        <?php
        $paged = isset($_REQUEST['pageId']) ? $_REQUEST['pageId'] : 1;
        $catAssetsPages = ceil($catAssetsNum/$pagingSize);
        $startPage = max(1, $paged - 3);
        $endPage = min($catAssetsPages, $paged + 3);
        $catAssetsPage = $netx->getAssetsByCategoryID($currentCatId, $paged);
        if($catAssetsPages > 1){
            echo '<div class="tablenav"><div class="tablenav-pages">';
            if($paged != 1){
                echo '<a class="next page-numbers" data-post-id="'.$postID.'" data-page-num="'.($paged-1).'">&laquo;</a>';
            }
            for($i = $startPage; $i <= $endPage; $i++){
                if($paged == $i){
                    echo '<span class="page-numbers current">'.$i.'</span>';
                } else {
                    echo '<a class="page-numbers" data-post-id="'.$postID.'" data-page-num="'.$i.'">'.$i.'</a>';
                }
            }
            if($paged != $catAssetsPages){
                echo '<a class="next page-numbers" data-post-id="'.$postID.'" data-page-num="'.($paged+1).'">&raquo;</a>';
            }
            echo '</div></div>';
        }

        $catAssetsPageFiltered = array();

        if(count($catAssetsPage) > 0) {
            foreach($catAssetsPage as $key=>$asset){
                $assetID = $asset->getAssetID();
                $ast = $netx->getAsset($assetID);

                $fileIsSupported = false;
                foreach($supportedFileTypes as $supportedFileType) {
                    $filename = $ast->getFile();

                    $substrlength = strlen('.' . $supportedFileType);
                    if(substr($filename, - $substrlength) === ('.' . $supportedFileType)) {
                        $fileIsSupported = true;
                        break;
                    }
                }

                if($fileIsSupported) {
                    $catAssetsPageFiltered[$key] = $asset;
                }
            }
        }

        if(count($catAssetsPageFiltered) > 0) {
            foreach($catAssetsPageFiltered as $key=>$asset){
                $assetID = $asset->getAssetID();
                $ast = $netx->getAsset($assetID);
                ?>
                <div id="media-item-<?php echo $assetID; ?>" class="media-item">
                    <img class="pinkynail toggle" style="margin-top: 3px; display: block;" alt="<?php echo $asset->getLabel1(); ?>" src="<?php echo $wrapper->netxThumbUrl($assetID) ?>" />
                    <a class="toggle describe-toggle-on" style="display: block;">Show</a>
                    <a class="toggle describe-toggle-off" style="display: none;">Hide</a>
                    <div class="filename toggle"><span class="title"><?php echo $asset->getLabel1(); ?></span></div>
                    <table class="slidetoggle describe" style="display:none;">

                        <thead id="media-head-<?php echo $assetID; ?>" class="media-item-info">
                        <tr valign="top">
                            <td id="thumbnail-head-<?php echo $assetID; ?>" class="">
                                <p><a target="_blank" href="<?php echo $wrapper->netxPreviewUrl($assetID); ?>"><img style="margin-top: 3px" alt="" src="<?php echo $wrapper->netxThumbUrl($assetID); ?>" class="thumbnail"></a></p>
                            </td>
                            <td>
                                <p><strong>File name:</strong> <?php echo $asset->getLabel2(); ?></p>
                                <p><strong>File type:</strong> <?php echo $asset->getLabel3(); ?></p>
                                <p><strong>File size:</strong> <?php echo $asset->getLabel4(); ?></p>
                                <p><strong>Upload date:</strong> <?php echo $asset->getLabel5(); ?></p>
                            </td></tr>

                        </thead>
                        <tbody>
                        <tr class="image-size">
                            <th valign="top" class="label" scope="row"><label for="asset[<?php echo $assetID; ?>][image-size]"><span class="alignleft">Size</span><br class="clear"></label></th>
                            <td class="field">
                                <div class="image-size-item"><input type="radio" value="thumbnail" id="image-size-thumbnail-<?php echo $assetID; ?>" name="asset[<?php echo $assetID; ?>][image-size]"><label for="image-size-thumbnail-<?php echo $assetID; ?>">Thumbnail</label> <label class="help" for="image-size-thumbnail-<?php echo $assetID; ?>">(150&nbsp;x&nbsp;150)</label></div>
                                <div class="image-size-item"><input type="radio" value="medium" id="image-size-preview-<?php echo $assetID; ?>" name="asset[<?php echo $assetID; ?>][image-size]"><label for="image-size-preview-<?php echo $assetID; ?>">Preview</label> <label class="help" for="image-size-full-<?php echo $assetID; ?>">(<?php echo $ast->getPreviewfilewidth(); ?>&nbsp;x&nbsp;<?php echo $ast->getPreviewfileheight(); ?>)</label></div>
                                <div class="image-size-item"><input type="radio" checked="checked" value="full" id="image-size-full-<?php echo $assetID; ?>" name="asset[<?php echo $assetID; ?>][image-size]"><label for="image-size-full-<?php echo $assetID; ?>">Full Size</label> <label class="help" for="image-size-full-<?php echo $assetID; ?>">(<?php echo $ast->getFilewidth(); ?>&nbsp;x&nbsp;<?php echo $ast->getFileheight(); ?>)</label></div>

                                <?php
                                if(!empty($ast->getViewNames())){
                                    foreach($ast->getViewNames() as $viewName) {
                                        if($viewName === 'previewXMP') {
                                            continue;
                                        }
                                        ?>
                                        <div class="image-size-item"><input type="radio" checked="checked" value="<?php echo $viewName ?>" id="image-size-full-<?php echo $assetID; ?>" name="asset[<?php echo $assetID; ?>][image-size]"><label for="image-size-full-<?php echo $assetID; ?>">View: </label> <label class="help" for="image-size-full-<?php echo $assetID; ?>"><?php echo $viewName ?></label></div>
                                        <?php
                                    }
                                }
                                ?>
                            </td>
                        </tr>

                        <?php if(isset($options["netx_access_token"]) && !empty(trim($options["netx_access_token"]))): ?>
                            <tr class="enable-download">
                                <th valign="top" class="label" scope="row"><label for="asset[<?php echo $assetID; ?>][enable-download]"><span class="alignleft">Enable Download</span><br class="clear"></label></th>
                                <td class="field">
                                    <div class="image-size-item">
                                        <input type="checkbox" value="enable-download" id="asset[<?php echo $assetID; ?>][enable-download]" name="asset[<?php echo $assetID; ?>][enable-download]">
                                        <label for="asset[<?php echo $assetID; ?>][enable-download]">Save as download link</label>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr class="submit">
                            <td></td>
                            <td class="savesend">
                                <input type="button" data-asset-id="<?php echo($assetID); ?>" value="Insert into Post" class="button netx-submit-button netx-add-item-submit" id="" name="send[<?php echo $assetID; ?>]">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php
            }
        }else{
            ?>
            <p class="netx-no-files-found-label">No Files Found</p>
            <?php
        }

        wp_die();
    }

    public function wrap_image_html($html, $id, $caption, $title, $align, $url, $size, $alt)
    {
        $enforceNetxDownload = get_post_meta($id, self::NETX_ENFORCE_DOWNLOAD_KEY, true);

        if(!empty($enforceNetxDownload) && $enforceNetxDownload === 'true') {
            $assetID = get_post_meta($id, self::NETX_ASSET_ID_KEY, true);
            $sizeStr = get_post_meta($id, self::NETX_ASSET_VIEWNAME_KEY, true);
            $filename = basename(get_attached_file($id));

            //awesome the magic strings used through out this code don't match the magic strings
            // in netx. Perfect.
            if ($sizeStr == 'thumbnail'){
                $sizeStr = 'thumb';
            }
            else if ($sizeStr == 'medium'){
                $sizeStr = 'preview';
            }
            else if ($sizeStr == 'full'){
                $sizeStr = 'original';
            }

            $downloadUrl = plugins_url('endpoints/asset.php', __FILE__);

            $downloadUrl .= '?assetID=' . $assetID;
            $downloadUrl .= '&sizeStr=' . $sizeStr;
            $downloadUrl .= '&filename=' . $filename;

            return '<a class="netx-download-link" href="' . $downloadUrl . '" target="_blank"><img src="' . wp_get_attachment_url($id) . '" /></a>';
        }

        return $html;
    }

    private function tree_node_view($node, $selectedId) {
        $treeNodeClass = 'netx-tree-node collapsed';
        $treeNodeLinkClass = 'netx-tree-node-link';
        $treeToggleIconClass = 'dashicons dashicons-plus';

        if($node['categoryId'] == $selectedId) {
            $treeNodeClass = 'netx-tree-node';
            $treeNodeLinkClass = 'netx-tree-node-link selected';
            $treeToggleIconClass = 'dashicons dashicons-minus';
        }
        ?>

        <li class="<?php echo($treeNodeClass); ?>">
            <?php if(count($node['children']) > 0) { ?>
                <a class="netx-tree-node-toggle">
                    <span class="<?php echo($treeToggleIconClass); ?>"></span>
                </a>
            <?php }else{ ?>
                <span class="netx-tree-node-nontoggle"><span class="dashicons dashicons-portfolio"></span></span>
            <?php } ?>
            <a class="<?php echo($treeNodeLinkClass); ?>" data-cat-id="<?php echo($node['categoryId']); ?>"><?php echo($node['label']); ?></a>
            <ul class="netx-tree-node-child">
                <?php foreach($node['children'] as $key => $child) {
                    $this->tree_node_view($child, $selectedId);
                } ?>
            </ul>
        </li>

        <?php
    }

    private function getRootCategoryFromTree($tree, $rootId) {
        if(is_array($tree)) {
            if(array_key_exists($rootId, $tree)) {
                return $tree[$rootId];
            }else{
                foreach($tree as $key => $child) {
                    if(is_array($child['children']) && (count($child['children']) > 0)) {
                        $recursiveResult = $this->getRootCategoryFromTree($child['children'], $rootId);

                        if($recursiveResult != null) {
                            return $recursiveResult;
                        }
                    }
                }
            }
        }

        return null;
    }

    private function AddMediaToLibraryFromNetx($wrapper, $netx, $assetID, $sizeStr, $directLink = false)
    {
        $ast = $netx->getAsset($assetID);

        $filename = $ast->getFile();
        $filesize = $ast->getFilesize();
        $filetype = $ast->getFiletypelabel();

        if($directLink) {
            $file = $wrapper->getAssetData($assetID, 't');
        } else {
            if ($sizeStr === 'full') {
                $file = $wrapper->getAssetData($assetID, 'o');
            } else if ($sizeStr === 'medium') {
                $file = $wrapper->getAssetData($assetID, 'p');
            } else if ($sizeStr === 'thumbnail') {
                $file = $wrapper->getAssetData($assetID, 't');
            } else {
                $file = $wrapper->getAssetData($assetID, $sizeStr);
            }
        }

        if (!$file['error']) {
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $file['file']);
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);

                //set custom netx metadata
                add_post_meta($attachment_id, NetXMediaManager::NETX_ASSET_ID_KEY, $assetID, true);
                add_post_meta($attachment_id, NetXMediaManager::NETX_ASSET_VIEWNAME_KEY, $sizeStr, true);

                $downloadUrl = plugins_url('endpoints/asset.php', __FILE__);

                if ($sizeStr == 'thumbnail'){
                    $sizeStr2 = 'thumb';
                }
                else if ($sizeStr == 'medium'){
                    $sizeStr2 = 'preview';
                }
                else if ($sizeStr == 'full'){
                    $sizeStr2 = 'original';
                } else {
                    $sizeStr2 = $sizeStr;
                }

                $downloadUrl .= '?assetID=' . $assetID;
                $downloadUrl .= '&sizeStr=' . $sizeStr2;
                $downloadUrl .= '&filename=' . $filename;

                add_post_meta($attachment_id, NetXMediaManager::NETX_FULL_PROXY_URL, $downloadUrl, true);
                add_post_meta($attachment_id, NetXMediaManager::NETX_FILE_SIZE, $filesize, true);
                add_post_meta($attachment_id, NetXMediaManager::NETX_FILE_NAME, $filename, true);
                add_post_meta($attachment_id, NetXMediaManager::NETX_FILE_TYPE, $filetype, true);

                if($directLink) {
                    add_post_meta($attachment_id, NetXMediaManager::NETX_ENFORCE_DOWNLOAD_KEY, "true", true);
                } else {
                    add_post_meta($attachment_id, NetXMediaManager::NETX_ENFORCE_DOWNLOAD_KEY, "false", true);
                }
            }
        }

        return array(
            "file" => $file,
            "attachment_id" => $attachment_id
        );
    }
}

add_action('init', array('NetXMediaManager', 'init'));

?>
