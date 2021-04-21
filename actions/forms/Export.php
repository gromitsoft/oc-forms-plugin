<?php

namespace GromIT\Forms\Actions\Forms;

use GromIT\Forms\Models\Form;
use GromIT\Forms\Traits\SelfMakeable;
use InvalidArgumentException;
use October\Rain\Support\Arr;

class Export
{
    use SelfMakeable;

    /**
     * Exports form to json.
     *
     * @see \GromIT\Forms\Actions\Forms\Import
     *
     * @param \GromIT\Forms\Models\Form $form
     *
     * @return string
     */
    public function execute(Form $form): string
    {
        $data = $this->buildConfig($form);

        return $this->encodeConfig($data);
    }

    private function buildConfig(Form $form): array
    {
        $data = [];

        $data['_meta'] = [
            'form_config' => true
        ];

        $data['form'] = Arr::except($form->toArray(), [
            'id',
            'created_at',
            'updated_at',
        ]);

        $data['form']['fields'] = collect($data['form']['fields'])
            ->map(static function (array $fieldData) {
                return Arr::except($fieldData, [
                    'id',
                    'form_id',
                    'created_at',
                    'updated_at',
                ]);
            });

        return $data;
    }

    /**
     * @noinspection PhpComposerExtensionStubsInspection
     */
    private function encodeConfig(array $data): string
    {
        $json = json_encode($data);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(
                'json_encode error: ' . json_last_error_msg()
            );
        }

        return $json;
    }
}
