<?php


/**
 * froentend  reg
 */
$app->get('/register', function () use ($app) {

    $formHash = String::uuid();
    session('form_hash', $formHash);

    $smsHash = String::uuid();
    session('sms_hash', $smsHash);
    $data = array('form_hash' => $formHash, 'sms_hash' => $smsHash);
    $app->view()->display('register.html', $data);
})->name('register');

/**
 * froentend  reg post ajax
 */
$app->post('/register', function () use ($app) {

    $mobile = $app->request->params('mobile');
    $password = $app->request->params('password');
    $mobileCode = $app->request->params('mobile_code');
    $email = $app->request->params('email');
    $code = $app->request->params('code');
    $hasRead = $app->request->params('hasread');
    $formHash = $app->request->params('form_hash');

    $ret = array(
        'error_code' => 0,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";
    } elseif (!$hasRead) {

        $ret['error_code'] = -1;
        $ret['error_msg'] = "请阅读并同意网站条款!";
    } elseif (!Util::verifyCode($code)) {

        $ret['error_code'] = -2;
        $ret['error_msg'] = "验证码不对!";
    } elseif (!Util::verifyMobileCode($mobile, $mobileCode)) {

        $ret['error_code'] = -3;
        $ret['error_msg'] = "手机验证码不对!";
    } elseif (!Util::isMobile($mobile)) {

        $ret['error_code'] = -4;
        $ret['error_msg'] = "手机号码格式不对!";
    } elseif (!Util::isEmail($email)) {

        $ret['error_code'] = -5;
        $ret['error_msg'] = "邮箱格式不对!";

    } elseif (Util::mobileExists($mobile)) {

        $ret['error_code'] = -6;
        $ret['error_msg'] = "手机号码已注册!";

    } elseif (Util::mobileExists($mobile)) {

        $ret['error_code'] = -7;
        $ret['error_msg'] = "邮箱已注册!";
    }

    // 如果参数ok, 则创建用户
    if (!$ret['error_code']) {

        $user = Model::factory('User')->create();
        $user->mobile = $mobile;
        $user->email = $email;
        $user->password = md5($password);
        $user->status = 1;
        $user->createdtime = time();
        $user->updatedtime = time();
        $r = $user->save();

        if ($r) {
            $ret['error_msg'] = "注册成功!";
            session('form_hash', null);

        } else {
            $ret['error_code'] = "-10";
            $ret['error_msg'] = "注册失败!";
        }
    }

    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {
            $app->flash('register_error', $ret['error_msg']);
            $app->redirect('/register');
            return;
        }

        $url = isset($_SESSION['after_register_url']) ? $_SESSION['after_register_url'] : '/';
        $app->redirect($url);
    }


})->name('register-post');

/**
 * froentend  reg
 */
$app->get('/login', function () use ($app) {

    $formHash = String::uuid();
    session('form_hash', $formHash);

    $data = array('form_hash' => $formHash);
    $app->view()->display('login.html', $data);
})->name('login');

/**
 * froentend  reg
 */
$app->get('/logout', function () use ($app) {

    session(null);
    $app->redirect('/');
})->name('logout');


/**
 * froentend  login post ajax
 */
$app->post('/login', function () use ($app) {

    $moible = $app->request->params('mobile');
    $pass = $app->request->params('password');
    $formHash = $app->request->params('form_hash');
    $ret = array(
        'error_code' => null,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";

    } else {
        $user = Model::factory('User')->where('mobile', $moible)->find_one();
        if (!$user || $user->password != md5($pass)) {

            $ret['error_code'] = -2;
            $ret['error_msg'] = "账号或密码不对";
        } else {
            $ret['error_code'] = 0;
            $ret['user'] = $user->as_array('id', 'name', 'sex', 'email', 'mobile', 'photo', 'description', 'status', 'createdtime', 'updatedtime');
            $_SESSION['uid'] = $ret['user']['id'];
            $_SESSION['userinfo'] = $ret['user'];
            session('form_hash', null);
        }
    }
    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {
            $app->flash('login_error', $ret['error_msg']);
            $app->redirect('/login');
            return;
        }

        $url = isset($_SESSION['after_login_url']) ? $_SESSION['after_login_url'] : '/';
        $app->redirect($url);
    }


})->name('login-post');

/**
 * froentend  member index
 */
$app->get('/member', function () use ($app) {

    $data = array();

    $user = Model::factory("User")->where_equal("id", session('uid'))->find_one();
    // 收货地址=
    $address =  $user->address()->find_one();
    if ($address) {
        $data['address']=  $address->as_array();
    } else {
        $data['address'] = null;
    }
    // 订单
    $orders =  $user->orders()->find_many();
    $data['orders'] = array();
    foreach ($orders as $order) {
        $data['orders'][] = $order->as_array();
    }

    // 评论
    $comments =  $user->comments()->find_many();
    $data['comments'] = array();
    foreach ($comments as $comment) {
        $commentArray = $comment->as_array();

        $images = $comment->commentImages()->find_many();
        $imageArray = array();
        foreach ($images as $image) {
            $imageArray[] = $image->as_array('image');
        }
        $commentArray['images'] = $imageArray;

        // 用户
        $uid = $commentArray['user_id'];
        $user = Model::factory('User')->find_one($uid);
        if ($user) {
            $commentArray['user'] = $user->as_array();
        } else {
            $commentArray['user'] = null;
        }

        if (!$user->name) {
            $commentArray['user']['name'] = substr_replace($user->mobile,'****',3,4);
        }
        // 时间处理
        $commentArray['strtime'] = Util::strfromtime($commentArray['createdtime']);

        $data['comments'][] = $commentArray;
    }

    $formHash = String::uuid();
    session('form_hash', $formHash);

    $data['form_hash'] = $formHash;
    $app->view()->display('/member/index.html', $data);
    return;
})->name('member');

/**
 * froentend  member info post ajax
 */
$app->post('/member', function () use ($app) {

    $name = String::fliterStr($app->request->params('name'));
    $email = $app->request->params('email');
    $description = String::fliterHtml($app->request->params('description'));
    $formHash = $app->request->params('form_hash');

    $ret = array(
        'error_code' => null,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";

    } elseif (!Util::isEmail($email)) {
        $ret['error_code'] = -5;
        $ret['error_msg'] = "邮箱格式不对!";

    } elseif (!Util::emailExists($email, session('uid'))) {

        $ret['error_code'] = -7;
        $ret['error_msg'] = "邮箱已注册!";
    } else {
        $user = Model::factory('User')->where('id', session('uid'))->find_one();
        if ($user ) {
            $user->name = $name;
            $user->email = $email;
            $user->description = $description;
            $user->updatedtime = time();
            if ($user->save()) {

                $ret['error_code'] = 0;
                $ret['error_msg'] = "修改成功!";
                $_SESSION['userinfo'] = $user->as_array('id', 'name', 'sex', 'email', 'mobile', 'photo', 'description', 'status', 'createdtime', 'updatedtime');
            } else {
                $ret['error_code'] = -2;
                $ret['error_msg'] = "数据错误!";
            }
            session('form_hash', null);
        }
    }
    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {

            $app->redirect('/member');
            return;
        }

        $url =  '/member';
        $app->redirect($url);
    }

    return;
})->name('member-post');

/**
 * froentend  photo post ajax
 */
$app->get('/member/photo', function () use ($app) {

    $formHash = String::uuid();
    session('form_hash', $formHash);
    $data = array();
    $data['form_hash'] = $formHash;
    $app->view()->display('/member/photo.html', $data);
    return;
})->name('member-photo-post');

/**
 * froentend  photo post ajax
 */
$app->post('/member/photo', function () use ($app) {

    $upfile = $_FILES['file'];

    $data = array();
    if (!session('uid')) {

        $data['error'] = "未登录, 请登录!";
        $data['jump_url'] = '/login';
    } elseif (!$upfile['size']) {
        $data['error'] = "上传失败!";
        $data['jump_url'] = '/member/photo';
    } else {

        $filename = Image::photo($upfile['tmp_name'], $app->request->params('w'), $app->request->params('h'), $app->request->params('x'), $app->request->params('y'));
        if ($filename) {

            $user = Model::factory('User')->where('id', session('uid'))->find_one();
            $user->photo = $filename;
            if ($user->save()) {
                $data = array('message' => '上传并修改成功!', 'jump_url' => '/member');
                $sess = session('userinfo');
                $sess['photo'] = $filename;
                session('userinfo', $sess);
            } else {
                $data = array('message' => '上传成功!', 'jump_url' => '/member');
            }
        }
    }
    if (!empty($data['error'])) {
        $app->view()->display('error.html', $data);
    } else {
        $app->view()->display('success.html', $data);
    }

})->name('member-photo-post');


/**
 * froentend  address post ajax
 */
$app->post('/member/address', function () use ($app) {

    $name = String::fliterStr($app->request->params('name'));
    $mobile = $app->request->params('mobile');
    $prov = $app->request->params('prov');
    $city = $app->request->params('city');
    $dist = $app->request->params('dist');
    $street = String::fliterHtml($app->request->params('street'));
    $formHash = $app->request->params('form_hash');

    $ret = array(
        'error_code' => null,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";

    } elseif (!Util::isMobile($mobile)) {
        $ret['error_code'] = -5;
        $ret['error_msg'] = "手机格式不对!";

    } else {
        $user = Model::factory('Address')->where('user_id', session('uid'))->find_one();
        if (!$user ) {
            $user = Model::factory('Address')->create();
        }

        $user->name = $name;
        $user->mobile = $mobile;
        $user->prov = $prov;
        $user->city = $city;
        $user->dist = $dist;
        $user->street = $street;
        $user->createdtime = time();
        $user->updatedtime = time();
        $user->address = $prov . $city . $dist . $street;
        if ($user->save()) {

            $ret['error_code'] = 0;
            $ret['error_msg'] = "添加或修改成功!";
        } else {
            $ret['error_code'] = -2;
            $ret['error_msg'] = "数据错误!";
        }
        session('form_hash', null);
    }
    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {

            $app->view()->display('/error.html', array('jump_url'=>'/member/', 'error'=>$ret['error_msg']));
            return;
        }

        $app->view()->display('/success.html', array('jump_url'=>'/member/', 'message'=>$ret['error_msg']));
    }

    return;
})->name('member-address-post');


/**
 * froentend  comments
 */
$app->get('/comment', function () use ($app) {

    $formHash = String::uuid();
    session('form_hash', $formHash);
    $data = array();
    $data['form_hash'] = $formHash;

    $p = (int)$app->request->params('p', 1);

    $p = $p > 1 ? $p : 1;
    $offset = ($p - 1) * 10;
    $comments = Model::factory('Comment')->order_by_desc("id")->offset(0)->limit(10)->find_many();

    $count = Model::factory('Comment')->count();
    $data['total'] = $count;
    $data['comments'] = array();
    foreach ($comments as $comment) {
        $commentArray = $comment->as_array();
        // 用户
        $uid = $commentArray['user_id'];
        $user = Model::factory('User')->find_one($uid);
        $commentArray['user'] = $user->as_array();
        if (!$user->name) {
            $commentArray['user']['name'] = substr_replace($user->mobile,'****',3,4);
        }
        // 时间处理

        $commentArray['strtime'] = Util::strfromtime($commentArray['createdtime']);
        // 关联图片
        $images = $comment->commentImages()->find_many();
        $imageArray = array();
        foreach ($images as $image) {
            $imageArray[] = $image->as_array('image');
        }
        $commentArray['images'] = $imageArray;
        // 关联回复
        $reply = $comment->commentReplys()->find_one();
        if ($reply) {
            $commentArray['reply'] = $reply->as_array('reply');
        }

        $data['comments'][] = $commentArray;
    }

    $app->view()->display('comment.html', $data);
    return;
})->name('member-comments-post');


/**
 * froentend  comments post ajax
 */
$app->post('/member/comment', function () use ($app) {

    $product = $app->request->params('product');
    $area = $app->request->params('area');
    $totalPrice = $app->request->params('total_price');
    $prov = $app->request->params('prov');
    $city = $app->request->params('city');
    $dist = $app->request->params('dist');
    $housingEstate = String::fliterHtml($app->request->params('housing_estate'));
    $content = String::fliterHtml($app->request->params('content'));

    $imgs = $app->request->params('imgs');

    $formHash = $app->request->params('form_hash');

    $ret = array(
        'error_code' => null,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";

    } elseif (empty($area)) {
        $ret['error_code'] = -2;
        $ret['error_msg'] = "面积不能为空!";

    }  elseif (empty($totalPrice)) {
        $ret['error_code'] = -3;
        $ret['error_msg'] = "总价不能为空!";

    }  elseif (empty($content)) {
        $ret['error_code'] = -4;
        $ret['error_msg'] = "评论内容不能为空!";

    } else {
        $obj = Model::factory('Comment')->create();

        $obj->product = $product;
        $obj->area = $area;
        $obj->prov = $prov;
        $obj->city = $city;
        $obj->dist = $dist;
        $obj->total_price = $totalPrice;
        $obj->user_id = session('uid');
        $obj->housing_estate = $housingEstate;
        $obj->content = $content;
        $obj->createdtime = time();
        $obj->updatedtime = time();

        $flag = $obj->save();
        if ($flag) {
            $srcs = explode(",", $imgs);
            if (is_array($srcs)) {

                foreach ($srcs as $src) {
                    if (empty($src)) {
                        continue;
                    }
                    $row = array();
                    $row['comment_id'] = $obj->id;
                    $row['image'] = $src;
                    $row['createdtime'] = time();
                    $img = Model::factory('CommentImage')->create($row);

                    $img->save();
                }
            }

            $ret['error_code'] = 0;
            $ret['error_msg'] = "发布成功!";
        } else {
            $ret['error_code'] = -2;
            $ret['error_msg'] = "数据错误!";
        }
        session('form_hash', null);
    }
    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {

            $app->view()->display('/error.html', array('jump_url'=>'/member/', 'error'=>$ret['error_msg']));
            return;
        }

        $app->view()->display('/success.html', array('jump_url'=>'/member/', 'message'=>$ret['error_msg']));
    }

    return;
})->name('member-comment-post');

/**
 * froentend  comments photo  post ajax
 */
$app->post('/member/comment/photo', function () use ($app) {

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
    return;
})->name('member-comment-photo-post');

/**
 * froentend  passwd
 */
$app->get('/member/passwd', function () use ($app) {

    $formHash = String::uuid();
    session('form_hash', $formHash);

    $data = array('form_hash' => $formHash);

    $app->view()->display('/member/passwd.html', $data);
    return;
})->name('member-passwd');
/**
 * froentend  passwd post ajax
 */
$app->post('/member/passwd', function () use ($app) {

    $password = $app->request->params('password');
    $pass1 = $app->request->params('pass1');
    $pass2 = $app->request->params('pass2');
    $formHash = $app->request->params('form_hash');
    $ret = array(
        'error_code' => null,
        'error_msg' => null
    );

    if ($formHash != session('form_hash')) {

        $ret['error_code'] = -10;
        $ret['error_msg'] = "非法请求!";

    } elseif ($pass1 != $pass2) {
        $ret['error_code'] = -1;
        $ret['error_msg'] = "密码确认不对!";

    } else {
        $user = Model::factory('User')->where('id', session('uid'))->find_one();
        if (!$user || $user->password != md5($password)) {

            $ret['error_code'] = -2;
            $ret['error_msg'] = "密码不对";
        } else {
            $user->password = md5($pass1);
            $user->updatedtime = time();;
            if ($user->save()) {
                $ret['error_code'] = 0;
            } else {
                $ret['error_code'] = -3;
                $ret['error_msg'] = "修改失败";
            }
        }
    }
    if ($app->request->isAjax()) {
        echo Util::toJson($ret);
        return;
    } else {

        if ($ret['error_code']) {
            $app->flash('pwd_error', $ret['error_msg']);
            $app->redirect('/member/passwd');
            return;
        }

        $app->redirect('/member');
    }

})->name('member-passwd-post');

