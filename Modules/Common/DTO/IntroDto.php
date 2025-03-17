<?php


namespace Modules\Common\DTO;


class IntroDto
{
    public $title;
    public $description;
    public $image;

    public function __construct($request) {
        if($request->get('title')) $this->title = $request->get('title');
        if($request->get('description')) $this->description = $request->get('description');
        if($request->get('image')) $this->image = $request->get('image');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->title == null) unset($data['title']);
        if($this->description == null) unset($data['description']);
        if($this->image == null) unset($data['image']);
        return $data;
    }
}
