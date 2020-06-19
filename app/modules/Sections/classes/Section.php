<?php

namespace modules\Sections;

class Section {
    public $title;
    public $decription;
    public $id;
    public $author;

    public function __construct(
        string $title,
        string $description,
        int $author,
        int $id = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->author = $author;
        $this->id = $id;
    }

    public function upload() : void {
        global $connect;

        var_dump($this->title);

        $connect->addTo('sections', [
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author
        ]);
    }

    public function update(
        string $title,
        string $description
    ) : void {
        global $connect;

        $connect->update('sections', [
            'title' => $title,
            'description' => $description
        ], 'id', $this->id);
    }

    public static function get(int $id) : object {
        global $connect;

        $sectionData = $connect->getFrom('sections', [], [
            'where' => ['id', $id]
        ])[0];

        return new Section(
            $sectionData['title'],
            $sectionData['description'],
            $sectionData['author'],
            $id
        );
    }

    public static function create(
        string $title,
        string $description,
        int $author
    ) : object {
        return new Section(
            $title,
            $description,
            $author
        );
    }

    public static function getAll() : array {
        global $connect;

        return $connect->getFrom('sections', []);
    }
}
