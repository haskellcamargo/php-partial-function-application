### Partial function application in PHP

#### Introduction

I'm a functional programming shiite, and I love dealing with functions. In languages like LiveScript or Haskell, we can make
a partial application of a funcional, this is, if a function doesn't receive the expected amount of parameters, we can
return another function to receive them. This is very useful when dealing with pure functions and data handling. In LiveScript,
we can do things such as:

```livescript
add = (x, y, z) --> x + y + z
add              # λ -> x + y + z
add 10           # λ -> 10 + y + z
add 10, 20       # λ -> 10 + 20 + z
add 10, 20, 30   # 10 + 20 + 30 -> 60
```

Realize that we return functions when the number of parameters doesn't satisfy the required number, therefore, we can build 
other functions over these, such as `add_10 = add 10`, and reuse it without modifiying the core of `add`!

All the implementations I saw in PHP were incomplete and with static arity, unable to partialize an already existing function,
therefore, I took 1 hour to make a LiveScript compatible version!

#### Currying vs Partial Application

We need to differentiate *currying* from *partial application*. According to Wikipedia:

> Currying is the technique of transforming a function that takes multiple arguments in such a way that it can be
> called as a chain of functions each with a single argument.

Currying, per se, may not be as useful as partial application, at least in non-pure languages, like PHP.

Partial application may be defined as, according to Wikipedia:

> In computer science, partial application (or partial function application) refers to the process of fixing a number
> of arguments to a function, producing another function of smaller arity.

That's exactly what we want!

#### Examples

```php
$add = partial(function($x, $y, $z) {
  return $x + $y + $z;
});

$a = $add;
$b = $add(10);
$c = $add(10, 20);
$d = $add(10, 20, 30);

echo $a(10, 20, 30), PHP_EOL;
echo $b(20, 30), PHP_EOL;
echo $c(30), PHP_EOL;
echo $d, PHP_EOL;
```

In all the results above we got 60 as output. You can also preinitialize a value while using `partial` and partialize
your already existing functions.

#### Basic usage

```php
require_once 'partial.php';

function add($x, $y, $z) {
  return $x, $y, $z;
}

$partial_add = partial('add');
// Enjoy!
```

#### How does it work?

`partial` receives a function and an arbitrarily defined number of arguments. We apply reflection on the received
function and check the number of arguments. We return a function that receives the rest of the arguments and,
when we match the required number, we evaluate the function with the passed parameters. When not, we recursively
call `partial` with the current arguments and the same function, until we reach the edge-case to evaluate it.
