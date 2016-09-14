<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * ��̨Ueditor������
 *
 * ��ط���
 *
 */

class UeditorController extends Controller {

    public function ingress(){
        date_default_timezone_set("Asia/chongqing");
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");

        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(C('WEB_DOMAIN').C('_ADMIN_JS_')."/ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
                /* �ϴ��ļ� */
            case 'uploadimage':
                $result = $this->doUpload();
                break;

            default:
                $result = json_encode(array(
                    'state'=> '�����ַ����'
                ));
                break;
        }

        /* ������ */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback�������Ϸ�'
                ));
            }
        } else {
            echo $result;
        }
    }

    public function doUpload(){
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(C('WEB_DOMAIN').C('_ADMIN_JS_')."/ueditor/php/config.json")), true);
        /* �ϴ����� */
        $base64 = "upload";
        $config = array(
            "pathFormat" => $CONFIG['imagePathFormat'],
            "maxSize" => $CONFIG['imageMaxSize'],
            "allowFiles" => $CONFIG['imageAllowFiles']
        );
        $fieldName = $CONFIG['imageFieldName'];

        $up = new \Yege\UeditorUploader($fieldName, $config, $base64);

        return json_encode($up->getFileInfo());
    }

}

