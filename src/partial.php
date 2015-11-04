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
  $start_parameters = array_slice(func_get_args(), 1);
  $required_size = sizeof((new \ReflectionFunction($fn))->getParameters());

  return function() use ($start_parameters, $required_size, $fn) {
    $rest_parameters = func_get_args();
    $remaining_size = $required_size - (count($rest_parameters) + count($start_parameters));

    $all_params = array_merge($start_parameters, $rest_parameters);

    if ($remaining_size <= 0) {
      return call_user_func_array($fn, $all_params);
    }

    array_unshift($all_params, $fn);
    return call_user_func_array('partial', $all_params);
  };
}
