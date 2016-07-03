import React from 'react';
import Currency from './currency.jsx'

export default class CurrencyList extends React.Component {
    render() {

        let currenciesList = [];
        this.props.data.forEach((currency) => {
            currenciesList.push("<Currency data={currency} />");
        });
        let commentNodes = this.props.data.map(function(currency) {
            return (
                <Currency key={currency.name} {...currency} />
            );
        });

        return (
            <div className="currencyList row text-center">
                {commentNodes}
            </div>
        );
    }
}