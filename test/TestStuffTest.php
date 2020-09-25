<?php

use PHPUnit\Framework\TestCase;

class TestStuffTest extends TestCase
{
    public function testTrue()
    {
        $this->assertEquals(
            '',
            filter_var('ÀÈÌÒÙỲǸẀÁÉÍÓÚÝĆǴḰĹḾŃṔŔŚẂŹÆæÐð♩♪♫♬♭♮♯±×÷√', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH)
        );
    }
}
