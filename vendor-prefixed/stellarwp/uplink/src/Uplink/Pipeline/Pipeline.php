<?php
/**
 * @license GPL-2.0-or-later
 *
 * Modified by James Kemp on 28-April-2025 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare( strict_types=1 );

namespace Iconic_WIS_NS\StellarWP\Uplink\Pipeline;

use Closure;
use RuntimeException;
use Iconic_WIS_NS\StellarWP\ContainerContract\ContainerInterface;
use Throwable;

/**
 * Adapted from Laravel's Pipeline to use our container system.
 *
 * @link https://github.com/illuminate/pipeline/blob/master/Pipeline.php
 */
final class Pipeline {

	/**
	 * The container implementation.
	 *
	 * @var ContainerInterface|null
	 */
	protected $container;

	/**
	 * The object being passed through the pipeline.
	 *
	 * @var mixed
	 */
	protected $passable;

	/**
	 * The array of class pipes.
	 *
	 * @var array
	 */
	protected $pipes = [];

	/**
	 * The method to call on each pipe.
	 *
	 * @var string
	 */
	protected $method = 'handle';

	/**
	 * Create a new class instance.
	 *
	 * @param  ContainerInterface|null  $container
	 *
	 * @return void
	 */
	public function __construct( ContainerInterface $container = null ) {
		$this->container = $container;
	}

	/**
	 * Set the object being sent through the pipeline.
	 *
	 * @param  mixed  $passable
	 *
	 * @return $this
	 */
	public function send( $passable ): Pipeline {
		$this->passable = $passable;

		return $this;
	}

	/**
	 * Set the array of pipes.
	 *
	 * @param  array|mixed  $pipes
	 *
	 * @return $this
	 */
	public function through( $pipes ): Pipeline {
		$this->pipes = is_array( $pipes ) ? $pipes : func_get_args();

		return $this;
	}

	/**
	 * Push additional pipes onto the pipeline.
	 *
	 * @param  array|mixed  $pipes
	 *
	 * @return $this
	 */
	public function pipe( $pipes ): Pipeline {
		array_push( $this->pipes, ...( is_array( $pipes ) ? $pipes : func_get_args() ) );

		return $this;
	}

	/**
	 * Set the method to call on the pipes.
	 *
	 * @param  string  $method
	 *
	 * @return $this
	 */
	public function via( string $method ): Pipeline {
		$this->method = $method;

		return $this;
	}

	/**
	 * Run the pipeline with a final destination callback.
	 *
	 * @param  \Closure  $destination
	 *
	 * @return mixed
	 */
	public function then( Closure $destination ) {
		$pipeline = array_reduce(
			array_reverse( $this->pipes() ), $this->carry(), $this->prepareDestination( $destination )
		);

		return $pipeline( $this->passable );
	}

	/**
	 * Run the pipeline and return the result.
	 *
	 * @return mixed
	 */
	public function thenReturn() {
		return $this->then( function ( $passable ) {
			return $passable;
		} );
	}

	/**
	 * Get the final piece of the Closure onion.
	 *
	 * @param  \Closure  $destination
	 *
	 * @return \Closure
	 */
	protected function prepareDestination( Closure $destination ): Closure {
		return function ( $passable ) use ( $destination ) {
			try {
				return $destination( $passable );
			} catch ( Throwable $e ) {
				return $this->handleException( $passable, $e );
			}
		};
	}

	/**
	 * Get a Closure that represents a slice of the application onion.
	 *
	 * @return \Closure
	 */
	protected function carry(): Closure {
		return function ( $stack, $pipe ) {
			return function ( $passable ) use ( $stack, $pipe ) {
				try {
					if ( is_callable( $pipe ) ) {
						// If the pipe is a callable, then we will call it directly, but otherwise we
						// will resolve the pipes out of the dependency container and call it with
						// the appropriate method and arguments, returning the results back out.
						return $pipe( $passable, $stack );
					} elseif ( ! is_object( $pipe ) ) {
						[ $name, $parameters ] = $this->parsePipeString( $pipe );

						// If the pipe is a string we will parse the string and resolve the class out
						// of the dependency injection container. We can then build a callable and
						// execute the pipe function giving in the parameters that are required.
						$pipe = $this->getContainer()->get( $name );

						$parameters = array_merge( [ $passable, $stack ], $parameters );
					} else {
						// If the pipe is already an object we'll just make a callable and pass it to
						// the pipe as-is. There is no need to do any extra parsing and formatting
						// since the object we're given was already a fully instantiated object.
						$parameters = [ $passable, $stack ];
					}

					$carry = method_exists( $pipe, $this->method )
						? $pipe->{$this->method}( ...$parameters )
						: $pipe( ...$parameters );

					return $this->handleCarry( $carry );
				} catch ( Throwable $e ) {
					return $this->handleException( $passable, $e );
				}
			};
		};
	}

	/**
	 * Parse full pipe string to get name and parameters.
	 *
	 * @param  string  $pipe
	 *
	 * @return array
	 */
	protected function parsePipeString( string $pipe ): array {
		[ $name, $parameters ] = array_pad( explode( ':', $pipe, 2 ), 2, [] );

		if ( is_string( $parameters ) ) {
			$parameters = explode( ',', $parameters );
		}

		return [ $name, $parameters ];
	}

	/**
	 * Get the array of configured pipes.
	 *
	 * @return array
	 */
	protected function pipes(): array {
		return $this->pipes;
	}

	/**
	 * Get the container instance.
	 *
	 * @throws \RuntimeException
	 *
	 * @return ContainerInterface
	 */
	protected function getContainer(): ContainerInterface {
		if ( ! $this->container ) {
			throw new RuntimeException( 'A container instance has not been passed to the Pipeline.' );
		}

		return $this->container;
	}

	/**
	 * Set the container instance.
	 *
	 * @param  ContainerInterface  $container
	 *
	 * @return $this
	 */
	public function setContainer( ContainerInterface $container ): Pipeline {
		$this->container = $container;

		return $this;
	}

	/**
	 * Handle the value returned from each pipe before passing it to the next.
	 *
	 * @param  mixed  $carry
	 *
	 * @return mixed
	 */
	protected function handleCarry( $carry ) {
		return $carry;
	}

	/**
	 * Handle the given exception.
	 *
	 * @param  mixed  $passable
	 * @param  \Throwable  $e
	 *
	 * @throws \Throwable
	 *
	 * @return mixed
	 */
	protected function handleException( $passable, Throwable $e ) {
		throw $e;
	}

}
