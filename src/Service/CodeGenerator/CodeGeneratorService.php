<?php


namespace App\Service\CodeGenerator;


class CodeGeneratorService
{

    public function generateCode(CodesInterface $repository): string
    {
        $codes = $repository->getAllEntityCodes(); //get all codes as an array

        $whileLoopState = true;
        while ($whileLoopState === true) {
            $newCode = $this->generateRandomString();
            $whileLoopState = false;
            foreach ($codes as $code) {
                if ($newCode === $code) {
                    $whileLoopState = true;
                }
            }
        }
        return $newCode;
    }

    public function generateRandomString($length = 6, $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}