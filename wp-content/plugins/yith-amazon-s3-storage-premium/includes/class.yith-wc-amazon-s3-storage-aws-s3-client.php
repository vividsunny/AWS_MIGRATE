<?php

use Aws\S3\Exception\S3Exception;
use Aws\Exception\AwsException;

class YITH_WC_Amazon_S3_Storage_Aws_S3_Client {
    /**
     * @var string
     */
    //public $_region = 'us-east-2';
    public $_region = null;

    public $_array_regions = null;

    /**
     * @var string
     */
    public $_version = 'latest';

    /**
     * @var string
     */
    public $bucket = null;

    /**
     * @var string
     */
    public $directory = '';

    /**
     * @var string
     */
    private $_key = null;

    /**
     * @var string
     */
    private $_secret = null;

    /**
     * @var S3Client|null
     */
    public $s3_client = null; //instancia de s3

    protected static $_instance = null;

    /**
     * instancia de s3
     * AwsS3 constructor.
     */
    public function __construct( $key, $secret ) {

        $this->_key    = $key;
        $this->_secret = $secret;
        $this->Load_Regions();
    }

    public function Init_S3_Client( $Region, $Version, $key, $Secret ) {

        $sdk = new Aws\Sdk( apply_filters('yith_wcamz_init_s3_client',array(
            'region'      => $Region,
            'version'     => $Version,
            'credentials' => array(
                'key'    => $key,
                'secret' => $Secret,
            )
        ) ));

        return $sdk->createS3();

    }

    public function Load_Regions() {

        $this->_array_regions = array(
            '0'  => array( 'us-east-2', 'US East (Ohio)' ),
            '1'  => array( 'us-east-1', 'US East (N. Virginia)' ),
            '2'  => array( 'us-west-1', 'US West (N. California)' ),
            '3'  => array( 'us-west-2', 'US West (Oregon)' ),
            '4'  => array( 'ca-central-1', 'Canada (Central)' ),
            '5'  => array( 'ap-south-1', 'Asia Pacific (Mumbai)' ),
            '6'  => array( 'ap-northeast-2', 'Asia Pacific (Seoul)' ),
            '7'  => array( 'ap-southeast-1', 'Asia Pacific (Singapore)' ),
            '8'  => array( 'ap-southeast-2', 'Asia Pacific (Sydney)' ),
            '9'  => array( 'ap-northeast-1', 'Asia Pacific (Tokyo)' ),
            '10' => array( 'eu-central-1', 'EU (Frankfurt)' ),
            '11' => array( 'eu-west-1', 'EU (Ireland)' ),
            '12' => array( 'eu-west-2', 'EU (London)' ),
            '13' => array( 'sa-east-1', 'South America (São Paulo)' ),
        );
    }

    public function Get_Regions() {
        return $this->_array_regions;
    }

    public static function get_instance() {
        $self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );

        if ( is_null( $self::$_instance ) ) {
            $self::$_instance = new $self;
        }

        return $self::$_instance;
    }

    public function Checking_Credentials() {

        try {

            // Instantiate the S3 client with your AWS credentials
            $S3_Client = $this->Init_S3_Client( $this->_array_regions[0][0], $this->_version, $this->_key, $this->_secret );

            $buckets = $S3_Client->listBuckets();

            update_option( 'YITH_WC_amazon_s3_storage_connection_success', 1 );

        } catch ( Exception $e ) {

            update_option( 'YITH_WC_amazon_s3_storage_connection_success', 0 );

            $buckets = 0;

        }

        return $buckets;

    }

    /**
     * obtiene todos los objetos de un bucket
     * @return \Guzzle\Service\Resource\ResourceIteratorInterface|mixed
     */
    public function Show_Buckets() {

        ob_start();

        $Bucket_Selected = ( get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) ? get_option( 'YITH_WC_amazon_s3_storage_connection_bucket_selected_select' ) : '' );

        try {

            // Instantiate the S3 client with your AWS credentials
            $S3_Client = $this->Init_S3_Client( $this->_array_regions[0][0], $this->_version, $this->_key, $this->_secret );

            $buckets = $S3_Client->listBuckets();

            echo "<option value='0'>" . __( 'Choose a bucket', 'yith-amazon-s3-storage' ) . "</option>";

            foreach ( $buckets['Buckets'] as $bucket ) {

                try {
                    $result = $S3_Client->getBucketLocation(array(
                        // Bucket is required
                        'Bucket' => $bucket['Name'],
                    ));
                } catch ( S3Exception $e ) {
                    //echo $e->getMessage() . "\n";
                    $result = false;
                }

                if ( $result ){

                    $selected = ( ( $Bucket_Selected == $bucket['Name'] . "_yith_wc_as3s_separator_" . $result['LocationConstraint'] ) ? 'selected="selected"' : '' );

                    ?>

                    <option <?php echo $selected; ?> value="<?php echo $bucket['Name'] . "_yith_wc_as3s_separator_" . $result['LocationConstraint']; ?>" ]> <?php echo $bucket['Name'] . " - " . $result['LocationConstraint']; ?> </option>

                    <?php

                }

            }

        } catch ( Exception $e ) {

            echo "<div>";

            echo "<p class='YITH_WC_amazon_s3_storage_error_accessing_class'>";

            $Path_error_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/access-error-logs.png';

            echo "<img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 35px;' src='$Path_error_image'/>";
            echo "<span class='YITH_WC_amazon_s3_storage_error_accessing_class_span'>";
            _e( 'An error occurred while accessing, the credentials (access key and secret key) are NOT correct', 'yith-amazon-s3-storage' );
            echo "</span>";

            echo "</p>";

            echo "</div>";

            //exit($e->getMessage());
        }

        return ob_get_clean();

    }

    public function Get_Presigned_URL( $Bucket, $Region, $Key ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        $cmd = $S3_Client->getCommand( 'GetObject', [
            'Bucket' => $Bucket,
            'Key'    => $Key
        ] );

        $valid_time = ( get_option( 'YITH_WC_amazon_s3_storage_time_valid_number' ) ? get_option( 'YITH_WC_amazon_s3_storage_time_valid_number' ) : '5' );

        $request = $S3_Client->createPresignedRequest( $cmd, '+'. $valid_time . ' minutes' );

        // Get the actual presigned-url
        return (string) $request->getUri();

    }

    public function Get_Access_of_Object( $Bucket, $Region, $Key ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        // Get an objectAcl
        $result = $S3_Client->getObjectAcl( array(
            'Bucket' => $Bucket,
            'Key'    => $Key
        ));

        $Access = __( 'Private', 'yith-amazon-s3-storage' );

        if ( isset( $result['Grants'][1] ) )
            if ( $result['Grants'][1]['Permission'] == 'READ' )
                $Access = __( 'Public', 'yith-amazon-s3-storage' );

        return $Access;

    }

    public function Show_Keys_of_a_Folder_Bucket( $Current_Folder, $Region, $File_Selected = 'none' ) {

        ob_start();

        $Array_Current_Folder = explode( "/", $Current_Folder );

        $Bucket = array_shift( $Array_Current_Folder );

        $Top_Folder = array_pop( $Array_Current_Folder );

        $Path_S3_image = constant( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) . '/images/s3.png';

        ?>

        <div class="YITH_WC_amazon_s3_storage_File_Manager_Main_Div_Padding">

            <div class="YITH_WC_amazon_s3_storage_ul_File_Manager_Bucket_Name">

                <img class='YITH_WC_amazon_s3_storage_error_accessing_class_img' style='width: 25px;' src='<?php echo $Path_S3_image; ?>' />

                <h3><?php echo _x( 'Bucket', 'S3 File Manager', 'yith-amazon-s3-storage' ); ?>: </h3>

                <div>
                    <a href='<?php echo $Bucket; ?>' data-region='<?php echo $Region; ?>' data-current_folder='<?php echo $Bucket; ?>'><?php echo $Bucket; ?></a>
                </div>

            </div>

            <div class="YITH_WC_amazon_s3_storage_ul_File_Manager_Bucket_Name">

                <h3><?php echo _x( 'Current folder', 'S3 File Manager', 'yith-amazon-s3-storage' ); ?>: </h3>

                <div>

                    <span>/</span>

                    <?php

                    $Current_Folder_Index = $Bucket;
                    foreach ( $Array_Current_Folder as $Folder ) {
                        $Current_Folder_Index = $Current_Folder_Index . "/" . $Folder;
                        echo "<span><a href='$Folder' data-region='$Region' data-current_folder='$Current_Folder_Index'>$Folder</a></span> <span>/</span>";
                    }


                    echo '<span>' . $Top_Folder . '</span>';

                    ?>

                </div>

            </div>

            <ul class="YITH_WC_amazon_s3_storage_ul_File_Manager">

                <?php

                $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

                // Register the stream wrapper from an S3Client object
                $S3_Client->registerStreamWrapper();

                if ( is_dir( "s3://" . $Current_Folder ) && ( $dh = opendir( "s3://" . $Current_Folder ) ) ) {

                    while ( ( $object = readdir( $dh ) ) !== false ) {

                        if ( is_dir( "s3://" . $Current_Folder . "/" . $object ) ) {

                            ?>

                            <li>
                                <label>
                                    <span class="dashicons dashicons-portfolio"></span>
                                    <span><a href='<?php echo $object; ?>' data-region='<?php echo $Region; ?>' data-current_folder='<?php echo $Current_Folder . "/" . $object; ?>'><?php echo $object; ?></a></span>
                                </label>
                            </li>

                            <?php

                        } else {

                            //$Access = ( is_readable( "s3://" . $Current_Folder . "/" . $object ) ? 'public' : 'private' );

                            $Key = $Current_Folder . "/" . $object;

                            $Key = str_replace( $Bucket . "/", "", $Key );

                            /*$result = $S3_Client->getObjectAcl( array(
                                'Bucket' => $Bucket,
                                'Key'    => $Key
                            ));

                            $Access = __( 'Private', 'yith-amazon-s3-storage' );

                            if ( isset( $result['Grants'][1] ) )
                                if ( $result['Grants'][1]['Permission'] == 'READ' )
                                    $Access = __( 'Public', 'yith-amazon-s3-storage' );*/



                            ?>

                            <li class="YITH_WC_amazon_s3_storage_ul_File_Manager_li_File">
                                <label>
                                    <input type="radio" name="S3_File" value="<?php echo $object; ?>" data-key="<?php echo $Key; ?>" <?php echo ( $File_Selected == $object ? 'checked' : '' ) ?>>
                                    <span class="dashicons dashicons-media-text"></span>
                                    <span><?php echo $object; ?></span>

                                </label>
                            </li>

                            <?php

                        }

                    }

                    closedir( $dh );

                }

                ?>

            </ul>

        </div>

        <?php

        return ob_get_clean();

    }

    public function yith_getObjectUrl( $Region, $Bucket, $File_Name ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        return $S3_Client->getObjectUrl( $Bucket, $File_Name );

    }

    public function get_base_url( $Bucket, $Region, $Keyname ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );
        $result = false;
        try {
            $result = $S3_Client->putObject( array(
                'Bucket'     => $Bucket,
                'Key'        => $Keyname,
                'Body'   => 'yith -> getting the base url',
                'ACL'    => 'public-read'
            ) );
        } catch (Exception $e) {

            ?>
                <div class="error">
                    <p><?php echo 'Amazon s3 Error:'; ?></p>

                    <p> <?php echo $e->getMessage();  ?> </p>

                </div>

            <?php
        }

        if ( ! $result ) {
            error_log( print_r( 'Error when uploading result_of_array', true ) );
            $base_url = 0;
        }
        else
            $base_url = str_replace( "/" .$Keyname , "", $result[ 'ObjectURL' ] );

        return $base_url;

    }

    public function Upload_Media_File( $Bucket, $Region, $array_files, $basedir_absolute, $private_or_public = 'public' ) {

        $base_folder = array_shift( $array_files );

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );


        $File_Name = array_shift( $array_files );


        if ( $base_folder != '' ) {
            $Key = $base_folder . "/" . $File_Name;
        } else {
            $Key = $File_Name;
        }

        if ( $base_folder != '' && !filter_var($base_folder, FILTER_VALIDATE_URL) ) {

            $SourceFile = $basedir_absolute . "/" . $base_folder . "/" . $File_Name;

        }elseif( filter_var( $base_folder, FILTER_VALIDATE_URL ) ){ //If $base_folder is an url, get the current year and month.
            //TODO check if the file is not upload in the same year/month on WordPress file
            $base_folder_date = date('Y/m');
            $SourceFile = $basedir_absolute . "/" . $base_folder_date . "/" . $File_Name;

        } else {

            $SourceFile = $basedir_absolute . "/" . $File_Name;
        }

        /*== We check if the file is going to be private or public ==*/
        $private_or_public = ( $private_or_public == 'private' ? $private_or_public : 'public-read' );


        $result = $S3_Client->putObject( array(
            'Bucket'     => $Bucket,
            'Key'        => $Key,
            'SourceFile' => $SourceFile,
            'ACL'        => $private_or_public,
            'Metadata'   => array(
                'wordpress' => wp_title(),
            )
        ) );

        if ( ! $result ) {
            error_log( print_r( 'Error when uploading result_of_array', true ) );
        }

        foreach ( $array_files as $File_Name ) {

            if ( $base_folder != '' ) {
                $Key = $base_folder . "/" . $File_Name;
            } else {
                $Key = $File_Name;
            }

            if ( $base_folder != '' ) {
                $SourceFile = $basedir_absolute . "/" . $base_folder . "/" . $File_Name;
            } else {
                $SourceFile = $basedir_absolute . "/" . $File_Name;
            }

            $result_of_array = $S3_Client->putObject( array(
                'Bucket'     => $Bucket,
                'Key'        => $Key,
                'SourceFile' => $SourceFile,
                'ACL'        => 'public-read',
                'Metadata'   => array(
                    'wordpress' => wp_title(),
                )
            ) );

            if ( ! $result_of_array ) {
                error_log( print_r( 'Error when uploading result_of_array', true ) );
            }

        }

        return $result;

    }

    public function delete_Objects_no_base_folder_yith( $Bucket, $Region, $array_files ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        $result = 0;

        foreach ( $array_files as $Key ) {

            $result = $S3_Client->deleteObject( array(
                'Bucket' => $Bucket,
                'Key'    => $Key
            ) );

        }

        return $result;
    }

    /**
     * elimina un objeto de un bucket
     *
     * @param $key
     */
    public function deleteObject_yith( $Bucket, $Region, $array_files ) {

        $base_folder = array_shift( $array_files );

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        $result = 0;

        foreach ( $array_files as $File_Name ) {

            if ( $base_folder != '' ) {
                $Key = $base_folder . "/" . $File_Name;
            } else {
                $Key = $File_Name;
            }

            $result = $S3_Client->deleteObject( array(
                'Bucket' => $Bucket,
                'Key'    => $Key
            ) );

        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function create_Bucket( $Bucket, $Region ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        try {
            $result = $S3_Client->createBucket( [
                'Bucket' => $Bucket,
            ] );
        } catch ( AwsException $e ) {
            // output error message if fails
            _e( "The bucket couldn't be created", constant( 'YITH_WC_AMAZON_S3_STORAGE_SLUG' ) );
            echo $e->getMessage();
            echo "\n";
        }

        return $result;

    }

    /**
     * download files
     *
     * @param $key
     * @param $filename
     */
    public function download_file( $Bucket, $Region, $array_files, $basedir_absolute ) {

        $S3_Client = $this->Init_S3_Client( $Region, $this->_version, $this->_key, $this->_secret );

        $base_folder = array_shift( $array_files );

        foreach ( $array_files as $File_Name ) {

            if ( $base_folder != '' ) {
                $Key = $base_folder . "/" . $File_Name;
            } else {
                $Key = $File_Name;
            }

            if ( $base_folder != '' ) {
                $SaveAs = $basedir_absolute . "/" . $base_folder . "/" . $File_Name;
            } else {
                $SaveAs = $basedir_absolute . "/" . $File_Name;
            }

            $result = $S3_Client->getObject( array(
                'Bucket' => $Bucket,
                'Key'    => $Key,
                'SaveAs' => $SaveAs
            ) );

        }

        return $result;

    }

    /**
     * @param $key
     *
     * @return \Guzzle\Service\Resource\Model
     */
    public function getObject( $key ) {
        $object = $this->s3_client->getObject( array(
            'Bucket' => $this->bucket,
            'Key'    => $key
        ) );

        return $object;
    }
}