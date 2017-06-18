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
        $user->setId(101);
        $user->setEmail('brendon@fraiser.com');
        $user->setPassword('immotep');
        $user->setFirstname('Brendon');
        $user->setLastname('Fraiser');
        $user->setCreatedAt(new \DateTime('2017-06-18 00:00:00'));

        $transformer = new UserViewTransformer();
        $response = $transformer->transform($user);

        $this->assertEquals([
            'id' => 101,
            'email' => 'brendon@fraiser.com',
            'firstname' => 'Brendon',
            'lastname' => 'Fraiser',
            'fullname' => 'Brendon Fraiser',
            'created_at' => '2017-06-18 00:00:00',
            'updated_at' => null,
        ], $response);
    }
}
