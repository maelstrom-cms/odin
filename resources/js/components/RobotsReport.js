import React from 'react';
import { Card, Button, Spin } from 'antd';
import ReactDiffViewer from 'react-diff-viewer'
import { formatDateTime, NoData } from '../helpers';

export default class RobotsReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        now: null,
        previous: null,
        loading: true,
    };

    componentDidMount() {
        this.update();
    };

    update = async(refresh = false) => {
        await this.setState({
            loading: true
        });

        let endpoint = this.props.endpoint;

        if (refresh) {
           endpoint += '?refresh=1';
        }

        const response = (await window.axios.get(endpoint)).data;

        this.setState({
            ...response,
            loading: false,
        });
    };

    forceUpdate = () => {
        return this.update(true);
    };

    renderReport = () => {
        const { now, previous } = this.state;

        if (!now) {
            return <NoData />
        }

        return (
            <>
                <div className="flex">
                    { previous && <div className="w-1/2"><h3>Before ({ previous && formatDateTime(previous.created_at) })</h3></div> }
                    <div className="w-1/2"><h3>Now ({ now && formatDateTime(now.created_at) })</h3></div>
                </div>

                <ReactDiffViewer
                    newValue={ now ? now.txt : '' }
                    oldValue={ previous ? previous.txt : ' ' }
                    splitView={ true }
                    showDiffOnly={ false }
                />
            </>
        )
    };

    renderBusy = () => {
        const { now, previous } = this.state;

        if (now && previous) {
            return this.renderReport()
        }

        return <Spin />
    };

    render() {
        const { loading } = this.state;

        return (
            <div>
                <Card
                    title="Robots.txt"
                    extra={ <Button loading={ loading } onClick={ this.forceUpdate }>Refresh</Button> }
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
