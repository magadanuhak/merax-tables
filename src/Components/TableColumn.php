<?php


namespace MeraxTables\Components;


class TableColumn
{
    private $name = '';
    private $sortable = true;
    private $showModalDelete = false;
    private $text = '';
    private $width = 0;


    /**
     * TableColumn constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);

        $this->setText($name);
        //$this->setText(\Helper::trans($name));
    }


    /**
     * @param string $name
     *
     * @return TableColumn
     */
    public static function create(string $name): self
    {
        return new self($name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return TableColumn
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     *
     * @return TableColumn;
     */
    public function setSortable(bool $sortable): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return TableColumn
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function toArray(): array
    {
        $result = array_filter([
            'value'           => $this->getName(),
            'sortable'        => $this->isSortable(),
            'text'            => $this->getText(),
            'width'           => $this->getWidth(),
            'showModalDelete' => $this->isShowModalDelete(),
        ]);

        return $result;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return TableColumn
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShowModalDelete(): bool
    {
        return $this->showModalDelete;
    }

    /**
     * @param bool $showModalDelete
     *
     * @return TableColumn
     */
    public function setShowModalDelete(bool $showModalDelete): self
    {
        $this->showModalDelete = $showModalDelete;

        return $this;
    }

}
