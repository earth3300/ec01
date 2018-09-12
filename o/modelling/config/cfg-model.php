<?php

defined( 'SITE' ) || exit;

/**
 * The "model" as defined here is the system that is designed to achieve a goal
 * or set of goals. Note that this definition may be different from that used in the
 * Model-View-Controller (MVC) architecture where the model may be more of an
 * _internal_ nature. Here we are concerned with the **model** as it applies to
 * real world problems, not just internal, code based ones. For example, the model
 * variables may have to do with maximums and minimums that are deemed acceptable around
 * a mean, such as the levels in a water tank that may be used to irrigate a garden.
 *
 * As such, we may want to differentiate these constants and their associated values from
 * those used internally to handle what is going in the site. One choice would be MDL_ or
 * MODEL_WATER_TANK_MAX and MODEL_WATER_TANK_MIN. These could even be inherited by a 3D rendering
 * engine or used with CSS or draw software to create an image of a water tank with the maximum
 * and minimum values set. If water in the tank goes below a certain level, then it needs to be
 * refilled.
 */

/** Minimum allowed in the water tank (liters). */
define('MODEL_WATER_TANK_MIN', 1000 );

/** Maximum allowed in the water tank (liters). */
define('MODEL_WATER_TANK_MIN', 10000 );

/** Base unit for liquids "L" as opposed to mL. */
define('MODEL_LIQUID_BASE_UNIT', 'L' );

/** Base unit for mass (as opposed to weight). */
define('MODEL_MASS_BASE_UNIT', 'kg' );
