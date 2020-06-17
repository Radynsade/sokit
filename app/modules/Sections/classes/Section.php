<?php

namespace modules\Sections;

class Section {
    public $title;
    public $decription;

    public function __construct(
        string $title,
        string $description
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->author = $_SESSION['user'];
    }

    public function upload() {
        global $connect;

        $connect->addTo('sections', [
            $this->title => 'title',
            $this->description => 'description',
            $this->author => 'author'
        ]);
    }
}
