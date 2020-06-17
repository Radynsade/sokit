<?php

class Section {
    public $title;
    public $decription;

    public function __construct(
        string $title,
        string $description
    ) {
        $this->title = $title;
        $this->description = $description;
    }

    public function upload() {
        global $connect;

        $connect->addTo('sections', [
            $encryptedLogin => 'username',
            Auth::hashPassword($newPassword) => 'password'
        ]);
    }
}
