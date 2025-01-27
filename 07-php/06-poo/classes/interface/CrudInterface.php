<?php 
namespace Classes\Interface;

interface CrudInterface
{
    public function create();
    public function read();
    public function update();
    public function delete();
}