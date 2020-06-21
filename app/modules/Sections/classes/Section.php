<?php

namespace modules\Sections;

use core\tools\Query;

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
        Query::with('sections')
            ->data([
                'title' => $this->title,
                'description' => $this->description,
                'author' => $this->author
            ])
            ->insert();
    }

    public function update(
        string $title,
        string $description
    ) : void {
        Query::with('sections')
            ->data([
                'title' => $title,
                'description' => $description
            ])
            ->where(['id' => $this->id])
            ->update();
    }

    public static function get(int $id) : object {
        $sectionData = Query::with('sections')
            ->where(['id' => $id])
            ->get();

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

        return Query::with('sections')->get();
    }
}
