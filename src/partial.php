<?php

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Haskell Camargo <marcelocamargo@linuxmail.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

function partial($fn)
{
  // Fetch the initial parameters on initialization
  $start_parameters = array_slice(func_get_args(), 1);
  $required_size = (new \ReflectionFunction($fn))->getNumberOfRequiredParameters();

  // When we have enough arguments to evaluate the function, the edge-case.
  if (sizeof($start_parameters) >= $required_size) {
    return call_user_func_array($fn, $start_parameters);
  }

  // When we must partialize it
  return function() use ($start_parameters, $required_size, $fn) {
    $rest_parameters = func_get_args();
    $remaining_size = $required_size - (count($rest_parameters) + count($start_parameters));

    // Join the current parameters with the newly received parameters
    $all_params = array_merge($start_parameters, $rest_parameters);

    // Append the function as the first item and call partialization again
    array_unshift($all_params, $fn);
    return call_user_func_array('partial', $all_params);
  };
}
