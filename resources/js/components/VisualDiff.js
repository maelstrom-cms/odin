import React, { useState, useEffect } from 'react';
import { Card, Button, Spin } from 'antd';
import { formatDateTime, NoData } from '../helpers';

const SingleDiff = ({diff}) => {
    const [expanded, setExpanded] = useState(false)

    const images = [
        {
            src: diff.current,
            title: 'Current Screenshot',
        },
        {
            src: diff.previous,
            title: 'Previous Screenshot',
        },
        {
            src: diff.diff,
            title: 'Differences',
        }
    ];

    useEffect(() => {

    }, [expanded])

    return <div>
        <div className={'flex items-center justify-between'}>
            <div>
                <Button target={'_blank'} href={diff.url} icon={'logout'}>{ diff.url }</Button>
            </div>
            <div>
                Found on <strong className={'font-bold'}>{ formatDateTime(diff.date) }</strong>
            </div>
        </div>
        <div className={'mt-8 flex flex-wrap overflow-hidden'} style={{ maxHeight: (expanded ? '100%' : 400) }}>
            {images.map(image => <div key={image.src} className={'w-1/3'}>
                <h3>{ image.title }</h3>
                <a target={'_blank'} href={image.src}>
                    <img loading={'lazy'} width={600} height={400} className={'mt-6 max-w-full w-auto h-auto'} src={image.src} alt={ image.title } />
                </a>
            </div>)}
        </div>
        <div className={'text-center mt-8'}>
            <Button type={'primary'} onClick={() => setExpanded(!expanded)}>
                { expanded ? 'Click to collapse' : 'Expand to see full screenshot' }
            </Button>
        </div>
    </div>
}

export default class VisualDiff extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        differences: [],
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
            differences: response,
            loading: false,
        });
    };

    forceUpdate = () => {
        return this.update(true);
    };

    renderReport = () => {
        const { differences } = this.state;

        if (!differences.length) {
            return <NoData />
        }

        return (
            <>
                <div>
                    { this.state.differences.map(diff => <SingleDiff key={diff.url} diff={diff} />)}
                </div>
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
                    title="Visual Differences"
                    extra={ <Button loading={ loading } onClick={ this.forceUpdate }>Refresh (This will take a while)</Button> }
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
