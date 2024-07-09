import { Component } from '@angular/core';
import { TreeNode } from 'primeng/api';
import { TreeNodeExpandEvent } from 'primeng/tree';
import { ArchiveService } from '../api';

@Component({
  selector: 'app-archive',
  templateUrl: './archive.component.html',
  styleUrl: './archive.component.scss'
})
export class ArchiveComponent {

  nodes: TreeNode[] = []

  constructor(private archiveService: ArchiveService) {
    archiveService.getAppArchiveGetconferences()
      .subscribe(res => {
        res.forEach(val => {
          this.nodes.push({
            key: val.id ?? '',
            label: val.name,
            leaf: false,
            type: 'conference',
            loading: false
          })
        })
      })
  }

  private setLoading(node: TreeNode, loading: boolean) {
    let parent = node.parent
    if (parent) {
      parent.loading = loading
    } else {
      node.loading = loading
    }
  }

  onNodeExpand(event: TreeNodeExpandEvent) {

    if (event.node.children) {
      // If children already loaded, return
      return
    }

    if (!event.node.key) {
      console.error('No key for node:', event.node);
      return
    }

    this.setLoading(event.node, true)

    if (event.node.type == 'conference') {
      this.archiveService.getAppArchiveGetinstances(event.node.key)
        .subscribe(res => {
          event.node.children = res.map(val => {
            return {
              key: val.id ?? '',
              label: val.year.toString(),
              leaf: false,
              type: 'instance',
              loading: false
            }
          })
          this.setLoading(event.node, false)
        })
    } else if (event.node.type == 'instance') {
      this.archiveService.getAppArchiveGetinstancedetails(event.node.key)
        .subscribe(res => {
          event.node.children = [{
            key: res.id ?? '',
            label: res.year.toString(),
            data: res,
            type: 'instanceDetail',
            loading: true
          }]
          this.setLoading(event.node, false)
        })
    } else {
      console.error('Invalid node type:', event.node.type);
      this.setLoading(event.node, false)
    }

  }
}
