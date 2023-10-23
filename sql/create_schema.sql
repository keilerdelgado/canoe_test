CREATE TABLE managers (
    id SERIAL PRIMARY KEY,
    company_name VARCHAR(255)
);

CREATE TABLE funds (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255),
    start_year INT,
    manager_id INT,
    FOREIGN KEY (manager_id) REFERENCES managers(id)
);

CREATE TABLE companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE aliases (
    id SERIAL PRIMARY KEY,
    alias VARCHAR(255),
    fund_id INT,
    FOREIGN KEY (fund_id) REFERENCES funds(id)
);

CREATE TABLE companies_funds (
    fund_id INT,
    company_id INT,
    PRIMARY KEY (fund_id, company_id),
    FOREIGN KEY (fund_id) REFERENCES funds(id),
    FOREIGN KEY (company_id) REFERENCES companies(id)
);
