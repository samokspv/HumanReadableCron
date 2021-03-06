# HumanReadableCron
Helps to convert human readable string or array data to cron string

## Examples:
```php

You can use string:

HumanReadableCron::convert('every 7 minutes');
Result: */7 * * * *

HumanReadableCron::convert('every 1 hour and 0 minutes');
Result: 0 */1 * * *

HumanReadableCron::convert('1 day of week and 1 hour and 15 minutes');
Result: 15 1 * * 1

HumanReadableCron::convert('5 day of 4 month and 0 hours and 1 minute');
Result: 1 0 5 4 *

HumanReadableCron::convert('12 day of month and 5 day of week and 12 hours and 12 minutes');
Result: 12 12 12 * 5

HumanReadableCron::convert('1 day of month and 6 hours and 10 minutes');
Result: 10 6 1 * *

You can use array:

HumanReadableCron::convert(array(
  'm' => array( // minute
      'clock' => 7,
      'period' => 'every'
  ),
  'h' => array( // hour
      'clock' => '*',
      'period' => 'clear'
  ),
  'dom' => array( // day of month
      'clock' => '*',
      'period' => 'clear'
  ),
  'mon' => array( // month
      'clock' => '*',
      'period' => 'clear'
  ),
  'dow' => array( // day of week
      'clock' => '*',
      'period' => 'clear'
  )
));
Result: */7 * * * *
```