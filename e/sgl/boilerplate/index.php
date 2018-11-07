<?php
/**
 * Boiler Plate
 *
 * Attempts to provide a reasonable, standards compliant pattern to emulate.
 *
 * File: index.php
 * Created: 2018-11-07
 * Update: 2018-11-07
 * Time: 08:36 EST
 */

/**
 * This class acts as an example on where to position a DocBlock.
 */
class Foo
{
    /** @var string|null $title contains a title for the Foo */
    protected $title = null;

    /**
     * Sets a single-line title.
     *
     * @param string $title A text for the title.
     *
     * @return void
     */
    public function setTitle($title)
    {
        // there should be no docblock here
        $this->title = $title;
    }
}
