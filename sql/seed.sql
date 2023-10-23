-- Insert data into Managers table
INSERT INTO managers (id, company_name)
VALUES
    (1, 'Company A'),
    (2, 'Company B'),
    (3, 'Company C');

-- Insert data into Funds table
INSERT INTO funds (id, name, start_year, manager_id)
VALUES
    (1, 'Fund 1', 2000, 1),
    (2, 'Fund 2', 2010, 2),
    (3, 'Fund 3', 2020, 3);

-- Insert data into Companies table
INSERT INTO companies (id, name)
VALUES
    (1, 'Alpha'),
    (2, 'Beta'),
    (3, 'Gamma'),
    (4, 'Delta'),
    (5, 'Epsilon'),
    (6, 'Zeta'),
    (7, 'Eta'),
    (8, 'Theta'),
    (9, 'Iota'),
    (10, 'Kappa');

-- Insert data into Aliases table
INSERT INTO aliases (alias, fund_id)
VALUES
    ('Fund 1 - Alias 1', 1),
    ('Fund 1 - Alias 2', 1),
    ('Fund 2 - Alias 1', 2),
    ('Fund 3 - Alias 1', 3),
    ('Fund 3 - Alias 2', 3);

-- Insert data into FundsCompanies table
INSERT INTO companies_funds (fund_id, company_id)
VALUES
    (1, 1), -- Fund 1 invested in Company X
    (1, 2), -- Fund 1 invested in Company Y
    (2, 2), -- Fund 2 invested in Company Y
    (2, 3), -- Fund 2 invested in Company Z
    (2, 4), -- Fund 2 invested in Company Alpha
    (3, 5), -- Fund 3 invested in Company Beta
    (3, 6), -- Fund 3 invested in Company Gamma
    (3, 7), -- Fund 3 invested in Company Delta
    (3, 8), -- Fund 3 invested in Company Epsilon
    (3, 9); -- Fund 3 invested in Company Omega

SELECT * FROM managers;