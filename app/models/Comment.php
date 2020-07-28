<?php
/**
 * Created by PhpStorm.
 * User: huchao
 * Date: 14-4-27
 * Time: 下午6:15
 */

class Comment extends Model
{

    public static $_table = 'comment';

    public function commentImages() {
        return $this->has_many('CommentImage');
    }

    public function commentReplys() {
        return $this->has_one('CommentReply');
    }

}