<?php

namespace Rendering;

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/table.php";

class Renderer
{
    protected $table;
    protected $specialFields;

    function __construct($table)
    {
        $this->table = $table;
    }

    // this is supposed to return string in case of success, how to check if error?

    public function renderRecord(array $data): string
    {
        $html = '';
        $html .= sprintf("<div class='%s'>", $this->table->getName());
        foreach (array_keys($this->table->getNeededFields()) as $field) {
            $html .= sprintf("<p class='%s'>%s</p>", $field, $data[$field]);
        }
        $html .= sprintf("</div>");

        return $html;
    }

    public function renderCreateForm(string $api, string $divClassName = ''): string
    {
        $html = sprintf("<div class='%s'>", $divClassName);
        $html .= sprintf("<form action='%s' method='post'>", $api);

        foreach ($this->table->getNeededFields() as $field) {
            $html .= sprintf("<label for='%s'>%s</label>", $field, $field);
            $html .= sprintf("<input type='text' name='%s' />", $field);
        }

        $html .= "<input type='submit' name='submit'/>";
        $html .= "</form>";
        $html .= "</div>";

        return $html;
    }


    public function renderInquireForm(string $api, string $divClassName = ''): string
    {
        $html = sprintf("<div class='%s'>", $divClassName);
        $html .= sprintf("<form action='%s' method='post'>", $api);

        foreach ($this->table->getUniqueFields() as $field) {
            $html .= sprintf("<label for='%s'>%s</label>", $field, $field);
            $html .= sprintf("<input type='text' name='%s' />", $field);
        }

        $html .= "<input type='submit' name='submit'/>";
        $html .= "</form>";
        $html .= "</div>";

        return $html;
    }


    public function getTable()
    {
        return $this->table;
    }

    public function setSpecialField($field, $type)
    {
        // TODO: $type must be a prefixed enum consisting of: file and picture
        $this->specialFields[$field] = $type;
    }
}