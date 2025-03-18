<?php


namespace Modules\Common\DTO;


class IntroDto
{
    public $title_ar;
    public $title_en;
    public $description_ar;
    public $description_en;
    public $image;

    public function __construct($request) {
        if($request->get('title_ar')) $this->title_ar = $request->get('title_ar');
        if($request->get('title_en')) $this->title_en = $request->get('title_en');
        if($request->get('description_ar')) $this->description_ar = $request->get('description_ar');
        if($request->get('description_en')) $this->description_en = $request->get('description_en');
        if($request->get('image')) $this->image = $request->get('image');
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if($this->title_ar == null) unset($data['title_ar']);
        if($this->title_en == null) unset($data['title_en']);
        if($this->description_ar == null) unset($data['description_ar']);
        if($this->description_en == null) unset($data['description_en']);
        if($this->image == null) unset($data['image']);
        return $data;
    }
}
