import React from 'react';

export default class Currency extends React.Component {
    render() {
        return (
            <div className="currency col-xs-3 col-centered">
                <span className="currency-name">{this.props.name}</span><div className="currency-rate">{this.props.rate}</div>
            </div>
        );
    }
}