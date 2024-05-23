<?php

namespace FluentConnect\Framework\Validator;

use FluentConnect\Framework\Support\Arr;

trait MessageBag
{
    /**
     * The default message bag.
     *
     * @var array
     */
    protected $bag = [
        'array'       => 'The :attribute must be an array.',
        'alpha'       => 'The :attribute must contain only alphabetic characters.',
        'alphanum'    => 'The :attribute must contain only alphanumeric characters.',
        'alphadash'   => 'The :attribute must contain only alphanumeric and _- characters.',
        'email'       => 'The :attribute must be a valid email address.',
        'date_format' => 'Unable to format the :attribute field from the :value format string.',
        'exists'      => 'The selected :attribute is invalid.',
        'in'          => 'The selected :attribute is invalid.',
        'not_in'          => 'The selected :attribute is invalid.',
        'max'         => [
            'numeric' => 'The :attribute may not be greater than :max.',
            'file'    => 'The :attribute may not be greater than :max kilobytes.',
            'string'  => 'The :attribute may not be greater than :max characters.',
            'array'   => 'The :attribute may not have more than :max items.',
        ],
        'mimes'       => 'The :attribute must be a file of type: :values.',
        'mimetypes'   => 'The :attribute must be a file of type: :values.',
        'min'         => [
            'numeric' => 'The :attribute must be at least :min.',
            'file'    => 'The :attribute must be at least :min kilobytes.',
            'string'  => 'The :attribute must be at least :min characters.',
            'array'   => 'The :attribute must have at least :min items.',
        ],
        'string'      => 'The :attribute must be a string.',
        'integer'     => 'The :attribute must be an integer.',
        'numeric'     => 'The :attribute must be a number.',
        'required'    => 'The :attribute field is required.',
        'required_if' => 'The :attribute field is required when :other is :value.',
        'same'        => 'The :attribute and :other must match.',
        'size'        => [
            'numeric' => 'The :attribute must be :size.',
            'file'    => 'The :attribute must be :size kilobytes.',
            'string'  => 'The :attribute must be :size characters.',
            'array'   => 'The :attribute must contain :size items.',
        ],
        'url'         => 'The :attribute format is invalid.',
        'unique'      => 'The :attribute attribute is already taken and must be unique.',
        'digits'      => 'The :attribute must be :digits characters.'
    ];

    /**
     * Generate a validation error message.
     *
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @param $originalRuleKey
     *
     * @return mixed
     */
    protected function generate($attribute, $rule, $parameters, $originalRuleKey = null)
    {
        $method = 'replace'.str_replace(
            ' ', '', ucwords(str_replace(['-', '_'], ' ', $rule))
        );

        $originalMessageKey = '';
        if(!empty($originalRuleKey)){
            $originalMessageKey = $originalRuleKey.'.'.$rule;
        }

        if ($this->hasMethod($method)) {
            return $this->$method($attribute, $parameters, $originalMessageKey);
        } else {
            return $this->generateDefaultMessage($attribute, $parameters);
        }
    }

    /**
     * Fallback message generator for the failed validation.
     * @param  string $attribute
     * @param  array $parameters
     * @return string
     */
    protected function generateDefaultMessage($attribute, $parameters)
    {
        $msg = "The {$attribute} field has been failed the validation";

        if ($parameters) {
             $msg .= " with parameter \"{$parameters[0]}\"";
        }

        return ($msg . '.');
    }


    /**
     * Get the replacement text of the error message.
     *
     * @param $customMessagesKey
     * @param $bagAccessor
     * @param $originalMessageKey
     *
     * @return string
     */
    protected function getReplacementText($customMessagesKey, $bagAccessor, $originalMessageKey = null)
    {
        if (isset($this->customMessages[$customMessagesKey])) {
            return $this->customMessages[$customMessagesKey];
        } elseif (isset($this->customMessages[$originalMessageKey])) {
            return $this->customMessages[$originalMessageKey];
        }
        return Arr::get($this->bag, $bagAccessor, '');
    }

    /**
     * Make bag accessor key.
     *
     * @param $attribute
     * @param $rule
     *
     * @return string
     */
    protected function makeBagKey($attribute, $rule)
    {
        $type = $this->deduceType(
            $this->getValue($attribute), $attribute
        );

        return $rule.'.'.$type;
    }

    /**
     * Replace all place-holders for the string rule.
     *
     * @param $attribute
     * @param $parameters
     * @param $originalMessageKey
     * @return string
     */
    protected function replaceString($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.string', 'string', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the int|integer rule.
     *
     * @param $attribute
     * @param $parameters
     * @param $originalMessageKey
     * @return string
     */
    protected function replaceInt($attribute, $parameters)
    {
        return $this->replaceInteger($attribute, $parameters);
    }

    /**
     * Replace all place-holders for the int|integer rule.
     *
     * @param $attribute
     * @param $parameters
     * @param $originalMessageKey
     *
     * @return string
     */
    protected function replaceInteger($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.integer', 'integer', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the alpha rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceAlpha($attribute, $parameters,  $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.alpha', 'alpha',  $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the alphanum rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceAlphanum($attribute, $parameters,  $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.alphanum', 'alphanum', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the alphadash rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceAlphadash($attribute, $parameters,  $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.alphadash', 'alphadash', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the required rule.
     *
     * @param $attribute
     * @param $parameters
     * @param $originalMessageKey
     *
     * @return string
     */
    protected function replaceRequired($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.required', 'required', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the required if rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceRequiredIf($attribute, $parameters,  $originalMessageKey)
    {
        if (preg_match('/\.\d\./', $attribute, $matches)) {
            $parameters[0] = str_replace(['.*.'], $matches, $parameters[0]);
        }
        
        $text = $this->getReplacementText($attribute.'.required_if', 'required_if', $originalMessageKey);

        $value = end($parameters);
        
        return str_replace([
            ':attribute', ':other', ':value'],
            [$attribute, $parameters[0], $value],
            $text
        );
    }

    /**
     * Replace all place-holders for the email rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceEmail($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.email', 'email', $originalMessageKey);

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the email rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceDateformat($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.date_format', 'date_format', $$originalMessageKey);

        return str_replace([':attribute', ':value'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the size rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceSize($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.size', $this->makeBagKey($attribute, 'size'),  $originalMessageKey);

        return str_replace([':attribute', ':size'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the min rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceMin($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.min', $this->makeBagKey($attribute, 'min'),  $originalMessageKey);

        return str_replace([':attribute', ':min'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the max rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceMax($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.max', $this->makeBagKey($attribute, 'max'), $originalMessageKey);

        return str_replace([':attribute', ':max'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the min rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceSame($attribute, $parameters, $originalMessageKey)
    {
        $text = $this->getReplacementText($attribute.'.same', 'same', $originalMessageKey);

        return str_replace([':attribute', ':other'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the url rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceUrl($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.url', 'url');

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the numeric rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceNumeric($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.numeric', 'numeric');

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the mimes rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceMimes($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.mimes', 'mimes');

        return str_replace([':attribute', ':values'], [$attribute, implode(', ', $parameters)], $text);
    }

    /**
     * Replace all place-holders for the mimetypes rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceMimetypes($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.mimetypes', 'mimetypes');

        return str_replace([':attribute', ':values'], [$attribute, implode(', ', $parameters)], $text);
    }

    /**
     * Replace all place-holders for the unique rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceUnique($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.unique', 'unique');

        return str_replace(':attribute', $attribute, $text);
    }

    /**
     * Replace all place-holders for the digits rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceDigits($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.digits', 'digits');

        return str_replace([':attribute', ':digits'], [$attribute, $parameters[0]], $text);
    }

    /**
     * Replace all place-holders for the array rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceArray($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.array', 'array');

        return str_replace([':attribute', ':array'], [$attribute], $text);
    }

    /**
     * Replace all place-holders for the in rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceIn($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.in', 'in');

        return str_replace([':attribute', ':in'], [$attribute], $text);
    }

    /**
     * Replace all place-holders for the not_in rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceNotIn($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.not_in', 'not_in');

        return str_replace([':attribute', ':not_in'], [$attribute], $text);
    }

    /**
     * Replace all place-holders for the exista rule.
     *
     * @param $attribute
     * @param $parameters
     *
     * @return string
     */
    protected function replaceExists($attribute, $parameters)
    {
        $text = $this->getReplacementText($attribute.'.exists', 'exists');

        return str_replace([':attribute', ':exists'], [$attribute], $text);
    }
}
