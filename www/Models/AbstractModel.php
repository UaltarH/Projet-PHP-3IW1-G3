<?php
namespace App\Models;

class AbstractModel{
    public function getColumns(): array {
        return get_object_vars($this);
    }
    public function getAllColumns(): array {
    return get_class_vars(get_class($this));
}
}