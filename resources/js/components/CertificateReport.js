import React from 'react';
import { Card, Button, Spin, Progress, Icon, Tree, Tag, notification } from 'antd';
import {formatDateTime, GREEN, RED, YELLOW} from "../helpers";
import { NoData } from '../helpers'

const TreeNode = Tree.TreeNode;

export default class CertificateReport extends React.Component {

    state = {
        website: JSON.parse(this.props.website),
        loading: true,
        issuer: null,
        domain: null,
        additional_domains: [],
        valid_from: null,
        valid_to: null,
        was_valid: false,
        did_expire: true,
        grade: 'N/A',
        expires_in: 'N/A',
    };

    componentDidMount() {
        this.update();
    };

    update = async(refresh = false) => {
        await this.setState({
            loading: true
        });

        let endpoint = window.location.href + '/ssl';

        if (refresh) {
           endpoint += '?refresh=1';
        }

        const response = (await window.axios.get(endpoint)).data;

        if (response.state) {
            notification.warning({
                message: 'SSL Checker',
                description: response.state,
                placement: 'bottomRight',
            });
        }

        this.setState({
            ...response,
            loading: false,
        });
    };

    forceUpdate = () => {
        return this.update(true);
    };

    renderLeft = () => {
        const color = grade => {
          switch (grade) {
              case 'A+':
                  return GREEN;
              case 'A':
                  return GREEN;
              case 'A-':
                  return GREEN;
              case 'B':
                  return YELLOW;
              case 'C':
                  return YELLOW;
              case 'D':
                  return RED;
              case 'E':
                  return RED;
              case 'F':
                  return RED;
              case 'N/A':
                  return YELLOW;
          }
        };

        return (
            <>
                <Progress
                    strokeColor={ color(this.state.grade) }
                    type="circle"
                    percent={100}
                    format={ () => <span className={`${ this.state.grade === 'N/A' ? 'text-4xl' : 'text-6xl'} font-bold`} style={{ color: color(this.state.grade) }}>{ this.state.grade }</span> }
                />

                { this.state.issuer && <div className="mt-8 pr-12">
                    <div>
                        <div className="font-bold mb-2">Issued By:</div>
                        { this.state.issuer }
                    </div>
                    <div className="mt-2">
                        <div className="font-bold mb-2">Issued On:</div>
                        { formatDateTime(this.state.valid_from) }
                    </div>
                </div> }
            </>
        )
    };

    renderRight = () => {
        return (
            <>
                <h4>Primary Domain</h4>
                <Tag color="green">{ this.state.domain }</Tag>

                <h4 className="mt-5">Additional Domains</h4>
                { this.state.additional_domains.map(i => <Tag onDoubleClick={ () => window.open(`https://${i}`) } className="mb-2" key={ i }>{ i }</Tag>) }

                <div className="flex flex-wrap mt-5">
                    <div className="w-1/2 pr-5">
                        <div className="font-bold mb-2">Expires At:</div>
                        { formatDateTime(this.state.valid_to) }
                    </div>
                    <div className="w-1/2">
                        <div className="font-bold mb-2">Valid Certificate:</div>
                        <div className="flex items-center" style={{ color: (this.state.was_valid ? GREEN : RED ) }}><Icon style={{ fontSize: 20, marginRight: 10 }} type={ this.state.was_valid ? 'check-circle' : 'close-circle' } /> { this.state.was_valid ? 'Yes' : 'No' }</div>
                    </div>
                    <div className="w-1/2 mt-5 pr-32">
                        <div className="font-bold mb-2">Expires In:</div>
                        { this.state.expires_in }
                    </div>
                    <div className="w-1/2 mt-5">
                        <div className="font-bold mb-2">Has Expired:</div>
                        <div className="flex items-center" style={{ color: (this.state.did_expire ? RED : GREEN ) }}><Icon style={{ fontSize: 20, marginRight: 10 }} type={ this.state.did_expire ? 'check-circle' : 'close-circle' } /> { this.state.did_expire ? 'Yes' : 'No' }</div>
                    </div>
                </div>
            </>
        )
    };

    renderReport = () => {
        if (!this.state.issuer) {
            return <NoData />
        }

        return (
            <div className="flex">
                <div className="w-1/4 pr-7">
                    { this.renderLeft() }
                </div>
                <div className="w-3/4">
                    { this.renderRight() }
                </div>
            </div>
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
                    title="SSL Report"
                    extra={ <Button loading={ loading } onClick={ this.forceUpdate }>Refresh</Button> }
                >
                    { !loading ? this.renderReport() : this.renderBusy() }
                </Card>
            </div>
        )
    }
}
