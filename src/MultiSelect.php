<?php

namespace ZiffDavis\Luna\MultiSelect;

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

    public function meta()
    {
        return array_merge([
            'options' => $this->options,
            'optionLabel' => $this->optionLabel,
            'optionValue' => $this->optionValue,
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
        if ($request->exists($requestAttribute) === false) {
            return;
        }

        $idsToAttach = collect(explode(',', $request->input($requestAttribute)));
        $relation = $model->$attribute();

        $currentRelationIds = $relation->get()->pluck('id');
        $relation->detach($currentRelationIds->diff($idsToAttach)->all());
        $relation->attach($idsToAttach->diff($currentRelationIds));
    }
}
