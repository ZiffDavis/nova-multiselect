<?php

namespace ZiffDavis\Nova\MultiSelect;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MultiSelect extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'multi-select';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * @var array of objects
     */
    private $options = [];

    /**
     * @var string
     */
    private $optionValue = 'id';

    /**
     * @var string
     */
    private $optionLabel = 'name';

    /**
     * @var int
     */
    private $maxItems;

    public function options($value)
    {
        return $this->withMeta(['options' => $value]);
    }

    public function placeHolder($value)
    {
        return $this->withMeta(['placeHolder' => $value]);
    }

    public function disabled($bool)
    {
        return $this->withMeta(['disabled' => $bool]);
    }

    public function optionLabel($optionLabel)
    {
        $this->optionLabel = $optionLabel;

        return $this;
    }

    public function optionValue($optionValue)
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    public function maxItems(int $maxItems)
    {
        $this->maxItems = $maxItems;

        return $this;
    }

    public function meta()
    {
        return array_merge([
            'options'     => $this->options,
            'optionLabel' => $this->optionLabel,
            'optionValue' => $this->optionValue,
            'maxItems'    => $this->maxItems,
        ], $this->meta);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        return function () use ($request, $requestAttribute, $model, $attribute) {
            if ($request->exists($requestAttribute) === false) {
                return;
            }
           
            $requestIds = collect();

            if ($request->input($requestAttribute)) {
                $requestIds = collect(explode(',', $request->input($requestAttribute)));
            }
           
            $relation = $model->{$attribute}();
           
            // this could potentially be sync()?
            $currentRelationIds = $relation->get()->pluck('id');
            
            // first detach ones missing from request
            $relation->detach($currentRelationIds->diff($requestIds)->all());
            
            // figure out whats left, and attach them
            $relation->attach(
                $requestIds->diff($currentRelationIds)->all()
            );
        };
    }
}
