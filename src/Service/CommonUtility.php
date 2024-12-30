<?php

namespace App\Service;

class CommonUtility
{
    public function getErrorMessages($form)
    {
        $errorString = 'There are some issue while processing.Please try again.';
        foreach ($form->all() as $child) {
            foreach ($child->getErrors() as $error) {
                $name = $child->getName();
                $errors[$name] = $error->getMessage();
            }
        }
        if (isset($errors) && count($errors) > 0) {
            $errorString = implode(', ', $errors);
        }

        return $errorString;
    }
}
