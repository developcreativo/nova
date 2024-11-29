<?php

namespace Laravel\Components\Fields;

trait SupportsDependentFields
{
    /**
     * List of field dependencies.
     *
     * @var array<int, \Laravel\Components\Fields\Dependent>
     */
    protected $fieldDependencies = [];

    /**
     * Register depends on to a field.
     *
     * @param  string|\Laravel\Components\Fields\Field|array<int, string|\Laravel\Components\Fields\Field>  $attributes
     * @param  (callable(static, \Laravel\Components\Http\Requests\NovaRequest, \Laravel\Components\Fields\FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOn($attributes, $mixin)
    {
        array_push($this->fieldDependencies, new Dependent($attributes, $mixin));

        return $this;
    }

    /**
     * Register depends on to a field on creating request.
     *
     * @param  string|\Laravel\Components\Fields\Field|array<int, string|\Laravel\Components\Fields\Field>  $attributes
     * @param  (callable(static, \Laravel\Components\Http\Requests\NovaRequest, \Laravel\Components\Fields\FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOnCreating($attributes, $mixin)
    {
        array_push($this->fieldDependencies, new Dependent($attributes, $mixin, 'create'));

        return $this;
    }

    /**
     * Register depends on to a field on updating request.
     *
     * @param  string|\Laravel\Components\Fields\Field|array<int, string|\Laravel\Components\Fields\Field>  $attributes
     * @param  (callable(static, \Laravel\Components\Http\Requests\NovaRequest, \Laravel\Components\Fields\FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOnUpdating($attributes, $mixin)
    {
        array_push($this->fieldDependencies, new Dependent($attributes, $mixin, 'update'));

        return $this;
    }
}
