import React from 'react';

export default class Currency extends React.Component {
    render() {
        let div_id = "div_" + this.props.name;

        return (
            <div id={div_id} className="currency col-xs-3 col-centered">
                <span className="currency-name">{this.props.name}</span><div className="currency-rate">{this.props.rate}</div>
            </div>
        );
    }
}