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

    /** @var array  Default options. */
    protected $opts = [
      'name-1' => 'value-1',
      'name-2' => 'value-2',
      'name-3' => 'value-3',
    ];

    /**
     * Construct
     *
     * This example allows default options to be set in with a property and then
     * merged with additional options passed via the constructor.
     *
     * @link https://stackoverflow.com/a/4550097/5449906
     *
     * @param array $more_opts
     */
    public function __construct( $more_opts = [] )
    {
      $this->opts = array_merge( $this->opts, $more_opts );
    }

    /**
     * Sets a single-line title.
     *
     * @param string $title A text for the title.
     *
     * @return void
     */
    public function setTitle( $title )
    {
        // there should be no docblock here
        $this->title = $title;
    }
}
