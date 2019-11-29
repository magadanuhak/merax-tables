<?php

namespace MeraxTables;

use MeraxTables\Components\TableColumn;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedSort;

class Table implements Init
{
    public $headers = [];
    public $actionProps = [];
    protected $fields = [];
    protected $exceptions = [];
    /**
     * @var LengthAwarePaginator
     */
    public $items;

    public function __construct()
    {
        $this->setExceptions(['id', '_actions', '_class']);
        $this->init();
    }


    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getItems(): LengthAwarePaginator
    {
        return $this->items;
    }

    /**
     * @param LengthAwarePaginator $items
     * @return Table
     */
    public function setItems(LengthAwarePaginator $items): self
    {
        $fields = $this->getFields() + array_flip($this->getExceptions());
        $items->getCollection()->transform(static function ($value) use ($fields) {
            return array_intersect_key($value->toArray(), $fields);
        });

        $this->items = $items;
        return $this;
    }

    public function addColumn(TableColumn $column): Table
    {
        $this->headers[$column->getName()] = $column;

        return $this;
    }

    public function getColumn(string $columnName): TableColumn
    {
        return $this->headers[$columnName];
    }

    public function build(): array
    {
        $headers = [];
        foreach ($this->getHeaders() as $column) {
            $headers[] = $column->toArray();
        }

        return [
            'headers' => $headers,
            'items'   => $this->getItems(),
            'actions'   => $this->getActionProps(),
        ];
    }

    public function init(): void
    {
        $module = request()->segments()[2];
        $fields = trans("modules.{$module}.fields");
        $this->setFields(array_flip(Auth::user()->getFields(request()->route()->getName())));
        $fields = array_intersect_key($fields, $this->getFields());
        $this->addColumn(TableColumn::create('id')->setText('ID'));
        foreach ($fields as $fieldName => $field) {
            $this->addColumn(TableColumn::create($fieldName)->setText($field['title']));
        }
    }

    public function getAllowedSorts(): array
    {
        $result = [];
        $controllerName = Str::before(class_basename(request()->route()->getController()), 'Controller');
        $namespace = "App\\Sorts\\{$controllerName}\\";
        foreach ($this->getHeaders() as $columnName => $columnObject) {
            if (!$columnObject->isSortable()) {
                continue;
            }
            $className = Str::finish($namespace, Str::studly($columnName));
            $result[] = class_exists($className) ? AllowedSort::custom($columnName, new $className()) : $columnName;
        }

        return $result;
    }

    /**
     * Получение свойств для действий
     *
     * @return array
     */
    public function getActionProps()
    {
        return $this->actionProps;
    }

    /**
     * Заполнение свойств для действий
     *
     * @param array $actionProps = [
     *     'create' ?: 'edit' ?: 'delete' ?: 'toggle_state' => [
     *          'modal' => true ?: false,
     *      ]
     * ]
     * @return Table
     */
    public function setActionProps(array $actionProps) : self
    {
        $this->actionProps = $actionProps;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * @param array $exceptions
     */
    public function setExceptions(array $exceptions): void
    {
        $this->exceptions = $exceptions;
    }
}
