<?php

namespace ElementorPipefy\Widgets;

class Post_Validator{

    private $data;
    private $messages = [];
    private static $fields = ['postid'];

    public function __construct($post_data){
        $this->data = $post_data;
    }

    public function validateForm(){
        foreach(self::$fields as $field){
            if(!array_key_exists($field, $this->data)){
                trigger_error("$field is not present in data");
                return;
            }
        }
        $this->validatePostID();
        return $this->messages;
    }

    private function validatePostID(){
        $val = trim($this->data['postid']);

        if (empty($val)){
            $this->addMessages('Post ID cannot be empty');

        } elseif(!filter_var($val, FILTER_VALIDATE_INT)){
            $this->addMessages('Post ID must bem a valid ID');

        } elseif('publish' === get_post_status($val)) {
            $this->addMessages(get_the_title($val));

        } else {
            $this->addMessages('ID do post não cadastrado');
        }
    }

    public function isSuccess(){
        $val = trim($this->data['postid']);
        if('publish' === get_post_status($val)){
            return true;
        } else {
            return false;
        };
    }

    private function addMessages($val){
        $this->messages = $val;
    }
}