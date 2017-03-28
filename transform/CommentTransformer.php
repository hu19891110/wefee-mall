<?php namespace addons\wefeemall\transform;

use addons\wefeemall\model\MallGoodsComments;

class CommentTransformer
{

    public function transform(MallGoodsComments $comment)
    {
        return [
            'comment_content' => $comment->comment_content,
            'created_at'      => $comment->created_at,
            'comment_photos'  => $comment->comment_photos,
        ];
    }

}