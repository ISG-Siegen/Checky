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
  loading = false

  constructor(private archiveService: ArchiveService) {
    this.loading = true
    archiveService.getAppArchiveGetconferences()
      .subscribe(res => {
        res.forEach(val => {
          this.nodes.push({
            key: val.id ?? '',
            label: val.name,
            leaf: false,
            type: 'conference'
          })
        })
        this.loading = false
      })
  }

  onNodeExpand(event: TreeNodeExpandEvent) {
    this.loading = true

    if (!event.node.key) {
      console.error('No key for node:', event.node);
      this.loading = false
      return
    }

    if (event.node.type == 'conference') {
      this.archiveService.getAppArchiveGetinstances(event.node.key)
        .subscribe(res => {
          event.node.children = res.map(val => {
            return {
              key: val.id ?? '',
              label: val.year.toString(),
              leaf: false,
              type: 'instance'
            }
          })
          this.loading = false
        })
    } else if (event.node.type == 'instance') {
      this.archiveService.getAppArchiveGetinstancedetails(event.node.key)
        .subscribe(res => {
          event.node.children = [{
            key: res.id ?? '',
            label: res.year.toString(),
            data: res,
            type: 'instanceDetail'
          }]
          this.loading = false
        })
    } else {
      console.error('Invalid node type:', event.node.type);
      this.loading = false
    }
  }
}
