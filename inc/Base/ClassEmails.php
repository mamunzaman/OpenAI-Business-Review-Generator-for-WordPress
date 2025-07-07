<?php

namespace Inc\Base;

use \Inc\Base\ClassBaseController;

class ClassEmails extends ClassBaseController
{

    public function register(){
        //add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets_admin') );
        //add_action( 'wp_enqueue_scripts', array($this, 'enqueue_assets_frontend') );
        //add_action( 'wp_enqueue_scripts', array($this, 'load_jquery') );

        //add_filter('wp_mail',array($this, 'emailSendForTokenNotification'));
        //$this->emailSendForTokenNotification();
    }

    public function emailSendForTokenNotification($arrayOfCompany){
        //return 'emailSendForTokenNotification';
        $to = 'test2@boovis.com';
        $subject = 'Neue Token-Anforderung E-Mail';
        //$body = 'My Data';
        $headers = array('Content-Type: text/html; charset=UTF-8;');
        $body = $this->EmailTemplateData($arrayOfCompany);

        wp_mail( $to, $subject, $body, $headers );

    }

    public function EmailTemplateData($arrayOfCompany){

        ob_start(); ?>
        <p>
            This is an E-Mail notification to contact <strong>Company.</strong>
        </p>

        <table class="column" style="border-spacing:0;width:100%;max-width:100%;vertical-align:top;
        display:inline-block;">

            <tr>
                <td width="160" style="padding:5px;"><strong>Company Name :</strong> </td>
                <td style="padding:5px;"> <?php echo $arrayOfCompany['company_name']; ?> </td>
            </tr>
            <tr>
                <td style="padding:5px;"> <strong>E-Mail :</strong>  </td>
                <td style="padding:5px;"> <?php echo $arrayOfCompany['email']; ?> </td>
            </tr>
            <tr>
                <td style="padding:5px;"> <strong>Link :</strong>  </td>
                <td style="padding:5px;">
                    <a href="<?php echo $arrayOfCompany['link']; ?>"><?php echo $arrayOfCompany['link']; ?><?php echo $arrayOfCompany['link']; ?></a></td>
            </tr>
            <tr>
                <td width="160" style="padding:5px;"><strong> WP Admin Link:</strong> </td>
                <td style="padding:5px;">
                    <a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $arrayOfCompany['id'];
                    ?>&action=edit"
                    ><?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $arrayOfCompany['id'];
                        ?>&action=edit</a>
                    </td>
            </tr>
        </table>




        <?php return ob_get_clean();
    }



}