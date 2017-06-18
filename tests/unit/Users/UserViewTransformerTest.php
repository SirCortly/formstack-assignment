<?php

use PHPUnit\Framework\TestCase;
use App\Users\{User, UserViewTransformer};

class UserViewTransformerTest extends TestCase
{
    /**
     * Test transform method
     */
    public function testTransform()
    {
        $user = new User();
        $user->setEmail('brendon@fraiser.com');
        $user->setPassword('immotep');
        $user->setFirstname('Brendon');
        $user->setLastname('Fraiser');

        $transformer = new UserViewTransformer();
        $response = $transformer->transform($user);

        $this->assertEquals([
            'email' => 'brendon@fraiser.com',
            'firstname' => 'Brendon',
            'lastname' => 'Fraiser',
            'fullname' => 'Brendon Fraiser',
        ], $response);
    }
}
