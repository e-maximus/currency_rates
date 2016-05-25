import React from 'react';
import ReactDOM from 'react-dom';
import CurrencyRates from './currency-rates.jsx';

ReactDOM.render(
    <CurrencyRates url="/api/rates.json" pollInterval={5000} />,
    document.querySelector('.root')
);
