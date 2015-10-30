Answer API Demo
===============

Car/Answer with RESTful API demo application

Installation
------------

```
git clone git@github.com:bigfoot90/demo-answer.git
cd demo-answer
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:create
php app/console hautelook_alice:doctrine:fixtures:load
php app/console server:run
```


Tests
-----

Launch PhpUnit inside the project's directory
```
phpunit
```

Test's second part
==================

The DQL query:
```sql
SELECT
    (CASE
        WHEN                    (c.year <= 1998) THEN    '0-1998'
        WHEN (c.year >= 1999 AND c.year <= 2002) THEN '1998-2002'
        WHEN (c.year >= 2003 AND c.year <= 2006) THEN '1993-2006'
        WHEN (c.year >= 2007 AND c.year <= 2009) THEN '2007-2009'
        WHEN (c.year >= 2010 AND c.year <= 2016) THEN '2010-2016'
        ELSE ''
    END) AS range,
    (
        SELECT COUNT(q3)
        FROM AppBundle:Car q1
        WHERE
            q1.producer = 'Audi'
            AND (CASE
                WHEN                     (q1.year <= 1998) THEN    '0-1998'
                WHEN (q1.year >= 1999 AND q1.year <= 2002) THEN '1998-2002'
                WHEN (q1.year >= 2003 AND q1.year <= 2006) THEN '1993-2006'
                WHEN (q1.year >= 2007 AND q1.year <= 2009) THEN '2007-2009'
                WHEN (q1.year >= 2010 AND q1.year <= 2016) THEN '2010-2016'
                ELSE ''
            END) = range
    ) AS Audi,
    (
        SELECT COUNT(q3)
        FROM AppBundle:Car q2
        WHERE
            q2.producer = 'BMW'
            AND (CASE
                WHEN                     (q2.year <= 1998) THEN    '0-1998'
                WHEN (q2.year >= 1999 AND q2.year <= 2002) THEN '1998-2002'
                WHEN (q2.year >= 2003 AND q2.year <= 2006) THEN '1993-2006'
                WHEN (q2.year >= 2007 AND q2.year <= 2009) THEN '2007-2009'
                WHEN (q2.year >= 2010 AND q2.year <= 2016) THEN '2010-2016'
                ELSE ''
            END) = range
    ) AS BMW,
    (
        SELECT COUNT(q3)
        FROM AppBundle:Car q3
        WHERE
            q3.producer = 'Mercedes'
            AND (CASE
                WHEN                     (q3.year <= 1998) THEN    '0-1998'
                WHEN (q3.year >= 1999 AND q3.year <= 2002) THEN '1998-2002'
                WHEN (q3.year >= 2003 AND q3.year <= 2006) THEN '1993-2006'
                WHEN (q3.year >= 2007 AND q3.year <= 2009) THEN '2007-2009'
                WHEN (q3.year >= 2010 AND q3.year <= 2016) THEN '2010-2016'
                ELSE ''
            END) = range
    ) AS Mercedes
FROM AppBundle:Car q
GROUP BY range
ORDER BY range ASC
```

wich results in:
```json
[
    {"range":    "0-1998", "Audi": "65", "BMW": "52", "Mercedes": "46"},
    {"range": "1998-2002", "Audi": "53", "BMW": "89", "Mercedes": "66"},
    {"range": "1993-2006", "Audi": "54", "BMW": "53", "Mercedes": "45"},
    {"range": "2007-2009", "Audi": "75", "BMW": "15", "Mercedes": "33"},
    {"range": "2010-2016", "Audi": "39", "BMW": "66", "Mercedes": "47"},
]
```

You can also extract the **CASE** into a custom DQL function ```YEAR_RANGE```
```sql
CASE
    WHEN                  (year <= 1998) THEN    '0-1998'
    WHEN (year >= 1999 AND year <= 2002) THEN '1998-2002'
    WHEN (year >= 2003 AND year <= 2006) THEN '1993-2006'
    WHEN (year >= 2007 AND year <= 2009) THEN '2007-2009'
    WHEN (year >= 2010 AND year <= 2016) THEN '2010-2016'
    ELSE ''
END
```

so the refactored query become shorter and more readable
```sql
SELECT
    YEAR_RANGE(c.year) AS range,
    (
        SELECT COUNT(q1)
        FROM AppBundle:Car q1
        WHERE
            q1.producer = 'Audi'
            AND YEAR_RANGE(q1.year) = range
    ) AS Audi,
    (
        SELECT COUNT(q2)
        FROM AppBundle:Car q2
        WHERE
            q2.producer = 'BMW'
            AND YEAR_RANGE(q2.year) = range
    ) AS BMW,
    (
        SELECT COUNT(q3)
        FROM AppBundle:Car q3
        WHERE
            q3.producer = 'Mercedes'
            AND YEAR_RANGE(q3.year) = range
    ) AS Mercedes
FROM AppBundle:Car q
GROUP BY range
ORDER BY range ASC
```