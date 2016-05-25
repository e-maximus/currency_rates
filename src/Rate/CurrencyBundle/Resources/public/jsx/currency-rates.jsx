import React from 'react';
import CurrencyList from './currency-list.jsx';

export default class CurrencyRates extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            currencies: []
        };
    }

    loadCommentsFromServer(data) {
        let resolve = (data) => this.setState({currencies: data.rates});
        let reject = (xhr, status, err) => console.error(this.props.url, status, err.toString());
        let currenciesPromise = new Promise((resolve, reject) => {
            $.getJSON(this.props.url, null)
                .then(resolve, reject);
        });
        currenciesPromise.then(resolve, reject);
    }

    componentDidMount() {
        this.loadCommentsFromServer();
        this.loadInterval = setInterval(this.loadCommentsFromServer.bind(this), this.props.pollInterval);
    }

    componentWillUnmount () {
        this.loadInterval && clearInterval(this.loadInterval);
        this.loadInterval = false;
    }

    render() {
        return (
            <div className="currencyBox">
                <div className="header-h1">Exchange currency rates</div>
                <CurrencyList data={this.state.currencies}/>
            </div>
        );
    }
}