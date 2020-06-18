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

    public function upload() : void {
        global $connect;

        $connect->addTo('sections', [
            $this->title => 'title',
            $this->description => 'description',
            $this->author => 'author'
        ]);
    }

    public function update(int $id) : void {
        global $connect;

        $connect->update('sections', [
            $this->title => 'title',
            $this->description => 'description'
        ], 'id', $id);
    }

    public static function get(int $id) : array {
        global $connect;

        return $connect->getFrom('sections', [], [
            'where' => ['id', $id]
        ])[0];
    }

    public static function getAll() : array {
        global $connect;

        return $connect->getFrom('sections', []);
    }
}
