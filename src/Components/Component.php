<?php
namespace MeraxTables\Components;

abstract class Component
{
    private $name = '';
    private $title = '';
    private $description = '';
    private $rules = '';
    protected $props = [];
    protected $component = '';

    public function __construct(string $name)
    {
        $this->setName($name);
        $module = request()->segments()[2];
        $titleAndDescription = __("modules.{$module}.fields.{$name}");
        if (is_array($titleAndDescription)) {
            $this->setTitle($titleAndDescription['title']);
            $this->setDescription($titleAndDescription['description']);
        }
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
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getRules(): string
    {
        return $this->rules;
    }

    /**
     * @param string $rules
     *
     * @return $this
     */
    public function setRules(string $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array
     */
    public function getProps(): array
    {
        return $this->props;
    }

    abstract public function toArray(): array;

    /**
     * @param array $data = [
     *                    [
     *                    'value' => int,
     *                    'text' => string
     *                    ]
     *                    ]
     *
     * @return $this
     */
    public function setData(array $data): self
    {
        //TODO нужен только для заполнени
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component;
    }

    /**
     * @param string $component
     *
     * @return Component
     */
    public function setComponent(string $component): self
    {
        $this->component = $component;

        return $this;
    }

}
