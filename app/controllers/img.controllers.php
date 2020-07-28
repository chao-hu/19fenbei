<?php
/**
 * Created by PhpStorm.
 * User: chao
 * Date: 15-2-9
 * Time: 下午11:05
 */


// 缩略图功能
$app->get('/pic', function () use ($app) {

    $id = $app->request()->params('id');
    $s = $app->request()->params('s');
    $w = $h = 0;
    if ($s && strpos($s, 'x')) {
        list($w, $h) = explode("x", $s);
    }

    $img = Model::factory('CommentImage')->find_one($id);
    if ($img && $img->image) {

        $file = ROOT . '/web/' . $img->image;
        if (!file_exists($file)) {

            echo "查无此图!";
            die;
        }
        $info = Image::getImageInfo($file);
        if (empty($info)) {

            echo "此图已损坏!";
            die;
        }
        $type = $info['type'];
        $mime = $info['mime'];

        if ($w && $h) {

            $tmp = explode(".", $file);
            $tmp[0] .= '-' . $s;
            $thumename = join(".", $tmp);
            $file = Image::thumb($file, $thumename, $type, $w, $h);
        }
        if (!$file) {
            echo "此图已损坏!";
            die;
        }

        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date(DATE_RFC822,strtotime(" 2 day")));

        header("Content-type: " . $mime);
        echo file_get_contents($file);
        die;
    }
    echo "查无此图!";
    die;
})->name('pic');

// 上传图片
$app->post('/pic', function () use ($app) {

    $accepts = array(
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/x-png",
        "image/png",
        "image/bmp"
    );

    $ret = array('error_code' => 0, 'error_msg' => '');
    $file = $_FILES['mypic'];

    if (!$file['size'] || $file['size'] > 5248000) {
        $ret['error_code'] = -1;
        $ret['error_msg'] = "上传失败";
    } elseif (!in_array($file['type'], $accepts)) {
        $ret['error_code'] = -2;
        $ret['error_msg'] = "格式不对" . $file['type'];
    } else {

        $filename = Image::img($file['tmp_name']);
        if (!$filename) {
            $ret['error_code'] = -3;
            $ret['error_msg'] = "上传存储失败";
        } else {
            $ret['error_msg'] = "上传成功!";
            $ret['src'] = $filename;
        }
    }

    echo Util::toJson($ret);
})->name('pic-upload');
