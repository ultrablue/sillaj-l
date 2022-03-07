/*
    Converts original Sillaj data to sillaj-l.
    Note the User Id in the WHERE clause!
*/

SELECT
    sillaj_task_intTaskId AS task_id,
    sillaj_project_intProjectId AS project_id,
    '1' AS user_id,
    timStart AS time_start,
    timEnd AS time_end,
    datEvent AS event_date,
    strRem AS note,
    datUpdate AS created_at,
    datUpdate AS updated_at,
    NULL AS deleted_at,
    (
        (
            (HOUR(timDuration) * 60) + MINUTE(timDuration)
        ) * 60
    ) AS duration,
    CONCAT(
        'PT',
        HOUR(timDuration),
        'H',
        MINUTE(timDuration),
        'M'
    ) AS iso_8601_duration
FROM
    `sillaj_event`
WHERE
    sillaj_event.sillaj_user_strUserId = 'gogogo'
