<?php

namespace GromIT\Forms\Actions\Forms;

use GromIT\Forms\Models\Field;
use GromIT\Forms\Models\Form;
use GromIT\Forms\Traits\SelfMakeable;
use Illuminate\Support\Facades\DB;

class Copy
{
    use SelfMakeable;

    /**
     * Creates form duplicate.
     *
     * @param \GromIT\Forms\Models\Form $form
     * @param string                    $name
     * @param string                    $key
     *
     * @return \GromIT\Forms\Models\Form
     * @throws \Throwable
     */
    public function execute(Form $form, string $name, string $key): Form
    {
        return DB::transaction(function () use ($key, $name, $form) {
            $newForm                = new Form();
            $newForm->name          = $name;
            $newForm->key           = $key;
            $newForm->is_active     = $form->is_active;
            $newForm->description   = $form->description;
            $newForm->wrapper_class = $form->wrapper_class;
            $newForm->form_class    = $form->form_class;
            $newForm->success_title = $form->success_title;
            $newForm->success_msg   = $form->success_msg;
            $newForm->extra_emails  = $form->extra_emails;
            $newForm->mail_template = $form->mail_template;
            $newForm->save();

            foreach ($form->fields as $field) {
                $fieldCopy          = new Field();
                $fieldCopy->form_id = $newForm->id;
                $fieldCopy->fill($field->only([
                    'label',
                    'key',
                    'comment',
                    'type',
                    'default',
                    'is_required',
                    'required_message',
                    'wrapper_class',
                    'field_class',
                    'options',
                ]));
                $fieldCopy->save();
            }

            return $newForm;
        });
    }
}
