<?php
declare(strict_types=1);
namespace App\App\Service\Command;

use Symfony\Component\Validator\Constraints;

/**
 * @property string $firstName Project name.
 * @property string $lastName Description.
 */
class GetUserInfoCommand
{
    /**
     * @Constraints\NotBlank()
     * @Constraints\Length(max = "25")
     */
    public string $firstName;

    /**
     * @Constraints\Length(max = "100")
     */
    public string $lastName;
}